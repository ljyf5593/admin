<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Admin模块的所有父类控制器
 *
 * @author  Jie.Liu (ljyf5593@gmail.com)
 * @Id $Id: admin.php 74 2012-09-02 08:43:11Z jie $
 */
class Controller_Admin extends Controller_Template {

    public $template = 'admin/template';

    /**
     * 当前用户可以执行的控制器
     */
    public $user_actions = array();

    /**
     * @var Media
     */
    protected $media;

    protected $title = 'Configuration Management System';

    /**
     * 当前登录的用户
     * @var null
     */
    protected $login_user = NULL;

    /**
     * 当前请求是否是模态弹出层
     */
    protected $is_modal = FALSE;

	/**
	 * 是否超级管理员
	 * @var bool
	 */
	protected $is_administrator = FALSE;

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

        $this->is_modal = $this->request->query('modal', FALSE);

        $action = $this->request->action();
        // 如果是增加和编辑动作，则使用模态弹出模板
        if ($this->is_modal AND in_array($action, array('create', 'edit'))) {
            $this->content = 'admin/edit_modal';
        }
        $this->content = View::factory($this->content)->set('action', $this->request->action());

        if(!$this->request->is_ajax()){
            $this->media = Media::get_instance();
            $this->css('css/bootstrap.min.css');
            $this->css('css/font-awesome.min.css');
            $this->css('css/bootstrap-responsive.min.css');
            $this->css('css/comasa.admin.css');

            $this->js('js/jquery.min.js');
            $this->js('js/jquery.form.js');
            $this->js('js/bootstrap.min.js');
            $this->js('js/bootbox.min.js');
            $this->js('DatePicker/WdatePicker.js');
            $this->media->editor_js();
            $this->js('js/admin.js');
        }

        $this->check_permissions($this->request);
        $this->nav = $this->get_nav();
    }

    public function after(){

        // 设置内容信息
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
     * 检测用户针对请求的权限
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
            $login_user = $auth_instance->get_user();
            $this->login_user = $login_user;

            // 如果是超级管理员直接返回
            if($login_user->username == ADMINISTRATOR){
				return $this->is_administrator = TRUE;
            }

            $this->user_actions = $this->get_permissions($this->login_user);

            $controller = strtolower($request->controller());

            // auth(登录) 和 home(首页) 不用权限判断
            if(!in_array($controller, array_merge($this->user_actions, array('auth', 'home')))) {

                $message = __('Access denied');

                if($request->is_ajax()){

                    $json['status'] = 'error';
                    $json['status_info'] = $message;
                    $body = json_encode($json);

                } else {
                    $body = $message;
                }
                echo $this->response->body($body);
                die();
            }
        }
    }

    /**
     * 页面跳转，整个页面跳转
     * @param $url
     * @param int $time
     */
    protected function location($url, $time = 2){
        if($this->request->is_ajax()) {
            $json['location'] = $url;
            $json['time'] = intval($time);
            $this->send_json($json);
        } else {
			HTTP::redirect($url);
        }
    }

    /**
     * 页面跳转，由ajax来操作,ajax内部跳转
     * @param $url
     * @param int $time
     */
    protected function jump($url, $time = 2){
        if($this->request->is_ajax()) {
            $json['url'] = URL::site($url, TRUE);
            $json['time'] = $time;
            $this->send_json($json);
        } else {
			HTTP::redirect($url);
        }
    }

    protected function redirect_login(){
        if ($this->request->action() !== 'login')
        {
            $this->set_status('error', 'Need login');
            $url = Route::url('admin/auth', array('action' => 'login'), TRUE);
            $this->location($url);
        }
    }

    private function get_permissions(Model_User $user) {
        $role_permissions = array();
        //首先获取他的所有角色
        $roles = $user->roles->find_all();
        foreach($roles as $role){

            $role_perms = json_decode($role->permissions, true);
            if(is_array($role_perms) AND !empty($role_perms)){
                foreach($role_perms as $perm){
                    $role_permissions[$perm] = $perm;
                }
            }
        }

        return $role_permissions;
    }

    /**
     * 根据用户角色获取导航信息
     * @return array|Kohana_Config_Group
     */
    private function get_nav(){
        $nav = Kohana::$config->load('admin');
        // 如果是超级管理员不做权限判断
        if ($this->is_administrator) {
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
