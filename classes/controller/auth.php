<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 用户登录、退出控制器，
 *
 * @author  Jie.Liu (ljyf5593@gmail.com)
 * @Id $Id: auth.php 33 2012-06-29 07:32:34Z Jie.Liu $
 */
class Controller_Auth extends Controller_Admin {

	private $message;

	public function before(){
		parent::before();
		if(!$this->request->is_ajax()){
			$this->template = View::factory('admin/login');
			$this->title = 'Login';
		}
	}

	public function action_login(){
		// Redirect logged-in admins to the administration index
		// All users which make it to the action are considered admins
		if (Auth::instance()->logged_in())

			$this->request->redirect(Route::url('admin', NULL, TRUE));

		if (HTTP_Request::POST == $this->request->method())
		{
			// Attempt to login user
			$user = Auth::instance()->login($this->request->post('username'), $this->request->post('password'), $this->request->post('remember') == 'remember');

			if (!$user){

				$this->message = '登录失败，请检查用户名和密码是否正确';

			} elseif(!$this->request->is_ajax()){

				$this->request->redirect(Route::url('admin', NULL, TRUE));

			}
		}
	}

	/**
	 * 退出登录
	 * @version 2011-8-12 下午02:48:24 Jie.Liu
	 */
	public function action_logout(){
		Auth::instance()->logout(TRUE);
		$url = Route::url('admin/auth', array('action'=>'login'), TRUE);
		$this->request->redirect($url);
	}

	public function after(){

		if($this->request->is_ajax()){

			if($this->message){

				 $this->set_status('error', $this->message);

			} else {

				$this->set_status('success', '登陆成功');

			}

		}

		parent::after();
	}
}
