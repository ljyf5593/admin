<?php defined('SYSPATH') or die('No direct script access.');
/**
 * CRUD操作控制器，实现数据库的基本CRUD操作信息
 *
 * @author  Jie.Liu (ljyf5593@gmail.com)
 * @Id $Id: crud.php 68 2012-08-28 10:16:13Z Jie.Liu $
 */
abstract class Controller_Admin_Crud extends Controller_Admin {

    protected $_model;

    public function before(){

        parent::before();

        if ($this->_model === NULL)	{
            $this->_model = $this->request->controller();
        }

        $model_name = 'Model_'. ucfirst($this->_model);
        if(!class_exists($model_name)){
            throw new Kohana_Exception('the model :model not found',
                array(':model' => $this->_model));
        }

    }

    public function action_index(){
        $this->action_list();
    }

    public function action_create(){
        $model = ORM::factory($this->_model);
        $this->main = $this->load_view('edit', $model);
        return $model;
    }

    public function action_list(){
        $this->main = $this->load_view('list');
        $model = ORM::factory($this->_model);

        //处理搜索结果
        $search_row = $model->getSearchRow();
        $model = $this->search_field($model, $search_row);

        $pagination = new Pagination(array(
            'total_items' => $model->reset(FALSE)->count_all(),
            'view' => 'pagination/admin',
        ));

        // 获取显示的列表列
        $list_row = $model->getListRow();

        // 获取排序相关参数
        list($order, $by) = $this->get_order_param('dateline');
        if($order AND isset($list_row[$order])){
            $model->order_by($order, $by);
        }

        $this->main->set(array(
            'model_list' => $model->limit($pagination->items_per_page)->offset($pagination->offset)->find_all(),
            'list_row' => $list_row,
            'search' => array('model' => $model, 'search_row' => $search_row),
            'batch_operations' => $model->getBatchOperation(),
            'pagination' => $pagination,
            'order' => $order,
            'by' => $by,
        ));
    }

    public function action_edit(){
        $id = intval($this->request->param('id'));
        $model = ORM::factory($this->_model, $id);
        $this->main = $this->load_view('edit', $model);
        if($model->loaded()){
            return $model;
        } else {
            $this->set_status('error', __('Data not found'));
            return NULL;
        }
    }

    public function action_update(){
        $id = intval($this->request->param('id'));
        $model = ORM::factory($this->_model, $id);
		$this->main = $this->load_view('edit', $model);
        try{

            if($this->request->post('dateline')){
                $this->request->post('dateline', strtotime($this->request->post('dateline')));
            }
            $model->values($this->request->post())->save();

            $this->set_status('success');
            if($this->request->is_ajax()){
                $this->send_json();
            }

        }catch (ORM_Validation_Exception $e){
			$message = $e->errors('validation');
			$this->main->message = $message;
            return $this->set_status('error', $message);
        }
    }

    public function action_delete(){
        $id = intval($this->request->param('id'));
        $model = ORM::factory($this->_model, $id);
        if($model->loaded()){
            $model->delete();
            $this->set_status('success');
        } else {
            $this->set_status('error', __('Data not found'));
        }
        $this->action_index();
    }

    /**
     * 批量操作
     */
    public function action_batch(){
        $action = $this->request->post('operation');
        $model = ORM::factory($this->_model);
        if(method_exists($model, $action)){
            $ids = $this->request->post('ids');
            if(!empty($ids)){
                $model_list = $model->where('id', 'IN', $ids)->find_all();

                foreach($model_list as $item){
                    $item->$action();
                }

                $this->set_status('success');
            } else {

                $this->set_status('error', __('Data not found'));
            }

        } else {

            $this->set_status('error', __('Method mot found'));

        }

        $this->action_list();
    }

    /**
     * 单个操作
     */
    public function action_single(){
        $operation = $this->request->param('operation');
        $id = $this->request->param('id');
        $model = ORM::factory($this->_model, $id);
        if($model->loaded()) {
            if(method_exists($model, $operation)) {
                $model->$operation();
                $this->set_status('success');
            } else {
                $this->set_status('error', __('Method mot found'));
            }
        }else {
            $this->set_status('error', __('Data not found'));
        }

        $this->action_list();
    }

    public function after(){
        // 设置全局的视图变量
        View::set_global('model_name', ucfirst($this->_model));

        parent::after();
    }

    /**
     * 加载视图文件
     * @param $file
     * @param $model  todo
     * @param $message 操作提示信息
     * @return View
     */
    protected function load_view($file, $model = NULL, $message = array()){
        $view_path = strtolower('admin/'.$this->_model.'/'.$file);

        if(Kohana::find_file('views', $view_path, 'php')){
            $view = View::factory($view_path);
        } else {
			$view = View::factory('admin/crud/'.$file);
        }

		if($model instanceof ORM){
			$view->list_columns = $model->list_columns();
			View::set_global('model', $model);
		}

		// 加载一个message信息
		$view->message = $message;

		return $view;
    }

    /**
     * 处理搜索信息,有缓存效果
     * @param ORM $model
     * @param $search_row
     * @return ORM 返回加入了搜索条件的ORM对象
     */
    protected function search_field(ORM $model, $search_row){
        // 获取搜索列
        $search = $this->request->query();

        // 查看当前次是否有新的搜索条件
        $where = array();
        foreach($search_row as $key => $value){
            if(isset($search[$key]) AND $search[$key] != ''){
                if(ORM::isFuzzyQuery($value)){
                    $where[] = array($key, 'LIKE', '%'.$search[$key].'%');
                }else {
                    $where[] = array($key, '=', $search[$key]);
                }
            }
        }

        // 如果这次没有需要新的搜索条件，则读取一次缓存中的搜索条件
        $cache_name = $this->_model.'where';
        if(empty($where)){
            $where = Kohana::cache($cache_name);

            // 缓存只读取一次,所以获取以后就删除
            Kohana::cache($cache_name, array());

        } else { // 否则缓存这一次的搜索参数
            Kohana::cache($cache_name, $where);
        }

        if( ! empty($where)) { // 如果获取到了搜索参数
            foreach($where as $condition){
                $model->where($condition[0], $condition[1], $condition[2]);
            }
        }

        return $model;
    }

    /**
     * 获取排序参数，有缓存效果
     */
    protected function get_order_param($default_order = NULL){
        $order = $this->request->param('order', $default_order);
        $by = $this->request->param('by', 'desc');
        $cache_name = $this->_model.'order';
        $result = array($order, $by);
        if($order){
            Kohana::cache($cache_name, $result);
        } else {
            $result = Kohana::cache($cache_name);
        }
        return $result;
    }

} // End Crud