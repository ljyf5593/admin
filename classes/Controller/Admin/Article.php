<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 文章管理系统
 *
 * @author  Jie.Liu (ljyf5593@gmail.com)
 * @Id $Id: user.php 33 2012-06-29 07:32:34Z Jie.Liu $
 */
class Controller_Admin_Article extends Controller_Admin_Crud {

	protected $top_actions = array(
		'menu' => array(
			'category' => array(
				'name' => 'Category',
				'icon' => 'icon-list-ul',
			),
		),
		'dropdown' => array(

		),
	);

	/**
	 * 文章栏目管理
	 */
	public function action_category(){
		$category = NULL;
		if(HTTP_Request::POST === $this->request->method()){
			$name = $this->request->post('name');
			if( is_array($name) AND !empty($name) ){
				foreach($name as $key=>$value){
					$category = new Model_Article_Category($key);
					if($category->loaded()){
						$category->name = $value;
						$category->save();
					}
				}
			}

			$newname = $this->request->post('newname');
			if( is_array($newname) AND !empty($newname) ){
				foreach($this->request->post('newname') as $key => $val){
					if(is_array($val) AND !empty($val)){
						foreach($val as $value){
							if($value){
								$category = new Model_Article_Category();
								$category->upid = $key;
								$category->name = $value;
								$category->save();
							}
						}
					}
				}
			}

			$this->set_status('success');
		}
		if(!($category instanceof Model_Article_Category)){
			$category = new Model_Article_Category();
		}
		$categorystr = $category->getCategoryHtm();
		$this->main = View::factory('admin/article/category');
		$this->main->category = $categorystr;
	}

	/**
	 * 删除文章栏目
	 */
	public function action_delcategory(){
		$catid = intval($this->request->param('id'));
		if($catid){
			$category = new Model_Article_Category($catid);
			if($category->loaded()){
				$count = ORM::factory('Article_Category')->where('upid', '=', $category->id)->count_all();
				if($count > 0){ // 如果该分类下有子分类，则不能删除
					$this->set_status('error',  __("You cann't delete this recored, its child element is not empty"));
				} else {
					$category->delete();
				}

			} else {
				$this->set_status('error', __('Data not found'));
			}
		}

		$this->jump('/admin/article/category');
	}

	public function action_update(){
		$id = intval($this->request->param('id'));
		$model = ORM::factory($this->_model, $id);
		$this->main = $this->load_view('edit', $model);
		if (HTTP_Request::POST == $this->request->method()){

			try {

				if($this->request->post('dateline')){
					$this->request->post('dateline', strtotime($this->request->post('dateline')));
				}

				$model->values($this->request->post());
				//自定义属性
				$model->attribute = $model->make_attr($this->request->post('attribute'));
				//添加文章内容
				$content = $this->request->post('content');
				if($model->description){
					$model->description = UTF8::substr($content, 0, Model_Article::DES_LEN);
				}
				$model->save();
				if (!empty($content)){
					$model->content->article_id = $model->id;
					$model->content->content = $content;
					$model->content->save();
					$tags = explode(',', $this->request->post('tags'));
					$model->addTags($tags);
				}

				$this->set_status('success');
				$this->action_list();

			} catch (ORM_Validation_Exception $e){

				$this->set_status('error', $e->errors('validation'));
			}

		}
	}

}
