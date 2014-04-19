<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 用户控制器
 *
 * @author  Jie.Liu (ljyf5593@gmail.com)
 * @Id $Id: user.php 33 2012-06-29 07:32:34Z Jie.Liu $
 */
class Controller_Admin_User extends Controller_Admin_Crud {

	protected $top_actions = array(
		/*'menu' => array(
			'permission' => array(
				'name' => 'Permissions',
				'icon' => 'icon-signin',
			),
		),
		'dropdown' => array(

		),*/
	);

	public function action_update(){
		$id = intval($this->request->param('id'));
		$model = ORM::factory('User', $id);
		$post_data = $this->request->post();
		$post_data['dateline'] = strtotime($post_data['dateline']);
		$post_data['last_login'] = strtotime($post_data['last_login']);

		try{
			if($model->loaded()){
				if(empty($post_data['password'])){
					unset($post_data['password']);
				}
				$model->update_user($post_data);
			} else {
				$model->create_user($post_data, array('active', 'email', 'username', 'password', 'dateline'));
			}
			$model->save();
			$model->update_roles($this->request->post('role'));
			$this->set_status('success');

		}catch (ORM_Validation_Exception $e){

			return $this->set_status('error', $e->errors('validate'));
		}

		$this->jump('/admin/user');
	}

	public function after() {
		View::set_global('roles', ORM::factory('Role')->find_all());

		parent::after();
	}
}
