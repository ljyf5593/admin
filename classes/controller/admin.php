<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Admin模块的所有父类控制器
 *
 * @author  Jie.Liu (ljyf5593@gmail.com)
 * @Id $Id: admin.php 74 2012-09-02 08:43:11Z jie $
 */
class Controller_Admin extends Controller_Template {

    public $template = 'admin/template';

    public $user_actions = array();

    protected $media;

    protected $title = 'Configuration Management System';

    /**
     * 当前登录的用户
     * @var null
     */
    protected $login_user = NULL;

    /**
     * 导航菜单
     * @var array
     */
    protected $nav = array();

    /**
     * 执行的结果
     * @var string
     */
    private $status = 'success';

    /**
     * 执行的结果信息
     * @var string
     */
    private $status_info = '';

    /**
     * 应用主要信息
     * @var null
     */
    protected $content ='admin/content';

    /**
     * 导航栏处显示的动作信息
     * @var array
     */
    protected $top_actions = array();

    /**
     * 包含Profiler 信息
     * @var null
     */
    public $main = NULL;

    public function before(){
        parent::before();
        if(!$this->request->is_ajax()){
            $this->media = Media::get_instance('admin');
            $this->css('css/bootstrap.css');
            $this->css('css/font-awesome.css');
            $this->css('css/bootstrap-responsive.css');
            $this->css('css/comasa.admin.css');

            $this->js('js/jquery.1.7.2.js');
            $this->js('js/jquery.form.js');
            $this->js('js/bootstrap.min.js');
            $this->js('js/bootbox.js');
            $this->js('DatePicker/WdatePicker.js');
            $this->js('kindEditor/kindeditor.js');
            $this->js('kindEditor/lang/zh_CN.js');
            $this->js('js/admin.js');
        }

        $this->check_permissions($this->request);
        $this->nav = $this->get_nav();
    }

    public function after(){

        // 设置内容信息
        $this->content = View::factory($this->content);
        $this->content->main = $this->main;
        $this->content->top_actions = $this->top_actions;
        $this->content->status = $this->status;
        $this->content->status_info = $this->status_info;
        if($this->request->is_ajax() AND $this->auto_render){

            $this->auto_render = FALSE;

            // 当前执行的状态为success时，不再发送内容信息
            if($this->status !== 'success'){
                $this->send_json();
            } else {
                $this->send_json(array('content' => (string)($this->content)));
            }

        } else {

            $this->template->title = $this->title;
            $this->template->media = $this->media;
            $this->template->user = $this->login_user;
            $this->template->nav = $this->nav;
            $this->template->content = $this->content;
            parent::after();
        }

    }

    /**
     * 将给定的数组按照json数据格式输出到浏览器
     * @param array $json
     */
    protected function send_json(array $json = array()){
        $response = array();
        $response['status'] = $this->status;
        $response['status_info'] = $this->status_info;
        echo $this->response->headers('Content-type', 'application/json; charset=UTF-8')->send_headers()->body(json_encode($response + $json));
        exit;
    }

    protected function css($css){
        $this->media->css('admin/'.$css);
    }

    protected function js($js){
        $this->media->js('admin/'.$js);
    }

    /**
     * 设置状态信息
     * @param string $status
     * @param null $status_info
     */
    protected function set_status($status = 'success', $status_info = NULL){
        $this->status = $status;
        if($status == 'success' AND $status_info === NULL){
            $status_info = __('Operation was successful');
        }
        $this->status_info = $status_info;
    }

    /**
     * Check permissions for a certain Request
     * 	Uses late static binding so child classes can override this
     * 	in order to replace its functionality
     *
     * @param	Request	$request
     */
    public function check_permissions(Request $request)
    {
        $auth_instance = Auth::instance();

		if ( ! $auth_instance->logged_in('login'))
		{
			#throw new HTTP_Exception_403('Access denied.');
			$this->redirect_login();

		} else {
			$this->login_user = $auth_instance->get_user();

			// 如果是超级管理员直接返回
			if($auth_instance->logged_in(ADMINISTRATOR)){
				return TRUE;
			}

			$this->user_actions = $this->login_user->get_permissions();

			$controller = strtolower($request->controller());

			// auth(登录) 和 home(首页) 不用权限判断
			if(!in_array($controller, array_merge($this->user_actions, array('auth', 'home')))) {

				$message = 'Access denied';

				if($request->is_ajax()){

					$json['status'] = 'error';
					$json['status_info'] = $message;
					$body = json_encode($json);

				} else {

					$body = $message;
				}
				echo $request->response()->body($body);die();
			}
		}
    }

    protected function redirect($url, $time = 2){
        if($this->request->is_ajax()) {
            $json['location'] = $url;
            $json['time'] = intval($time);
            $this->send_json($json);
        } else {
            $this->request->redirect($url);
        }
    }

    /**
     * 页面跳转，由ajax来操作
     * @param $url
     * @param int $time
     */
    protected function jump($url, $time = 2){
        if($this->request->is_ajax()) {
            $json['url'] = $url;
            $json['time'] = $time;
            $this->send_json($json);
        } else {
            $this->request->redirect($url);
        }
    }

    protected function redirect_login(){
        if ($this->request->action() !== 'login')
        {
            $this->set_status('error', 'Need login');
            $url = Route::url('admin/auth', array('action' => 'login'), TRUE);
            $this->redirect($url);
        }
    }

    private function get_nav(){
        $nav = Kohana::$config->load('admin');

		// 如果是超级管理员不做权限判断
        if (Auth::instance()->logged_in(ADMINISTRATOR)) {
            return $nav;
        } else {
			$user_nav = array();
			foreach($nav as $name => $sub_nav){
				foreach($sub_nav as $key => $value){
					if(in_array($key, $this->user_actions)){
						$user_nav[$name][$key] = $value;
					}
				}
			}

			return $user_nav;
		}
    }
} // End Admin
