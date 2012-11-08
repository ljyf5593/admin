<?php defined('SYSPATH') or die('No direct script access.');

/**
 * 后台欢迎界面
 *
 * @author  Jie.Liu (ljyf5593@gmail.com)
 * @Id $Id: home.php 33 2012-06-29 07:32:34Z Jie.Liu $
 */
class Controller_Admin_Home extends Controller_Admin {

	public function action_index(){
		$this->action_info();
	}

	public function action_info(){
		$this->main = View::factory('admin/home/info');
		$dataConfig = new Config_Database();
		Kohana::$config->attach($dataConfig);

		//加载或者创建一个新分组
		$config = Kohana::$config->load('site');
		$this->main->config = $config;

		//保存数据
		if (HTTP_Request::POST == $this->request->method()){
			foreach ($this->request->post() as $key=>$value){
				$config[$key] = $value;
			}

			$this->main->message = __('update_success', array('model'=>'配置'));
		}

		Kohana::$config->detach($dataConfig);
	}
}
