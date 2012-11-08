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

        $order = $this->request->param('order');
        $by = $this->request->param('by', 'desc');
        if($order){
            $model->order_by($order, $by);
        }

        $this->main->set(array(
            'model_list' => $model->limit($pagination->items_per_page)->offset($pagination->offset)->find_all(),
            'list_row' => $model->getListRow(),
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

        }catch (ORM_Validation_Exception $e){

            return $this->set_status('error', $e->errors('authmsg'));
        }

        $this->action_index();
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
     * @return View
     */
    protected function load_view($file, $model = NULL){
        $view_path = strtolower('admin/'.$this->_model.'/'.$file);

        if(Kohana::find_file('views', $view_path, 'php')){
            $view = View::factory($view_path);
        } else {
			$view = View::factory('admin/crud/'.$file);
        }

		if($model instanceof ORM){
			$view->list_columns = $model->list_columns();
			$view->model = $model;
			$view->action = $this->request->action();
		}

		return $view;
    }
    
    /**
     * 处理搜索信息
     * @param ORM $model
     * @param $search_row
     * @return ORM 返回加入了搜索条件的ORM对象
     */
    protected function search_field(ORM $model, $search_row){
        // 获取搜索列
        $search = $this->request->query();
        foreach($search_row as $key => $value){
            if(isset($search[$key]) AND $search[$key] != ''){
                if(ORM::isFuzzyQuery($value)){
                    $model->where($key, 'LIKE', '%'.$search[$key].'%');
                }else {
                    $model->where($key, '=', $search[$key]);
                }
            }
        }

        return $model;
    }

} // End App
