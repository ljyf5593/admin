<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 站点设置控制器.
 *
 * @author  Jie.Liu (ljyf5593@gmail.com)
 * @Id $Id: setting.php 238 2013-03-20 09:06:49Z Jie.Liu $
 *
 */
class Controller_Admin_Setting extends Controller_Admin {

    /**
     * 面包屑右边的菜单
     * @var array
     */
    protected $top_actions = array(
        'menu' => array(
            // 水印设置
            'watermark' => array(
                'name' => 'Watermark',
                'icon' => 'icon-magic',
            ),
            // 第三方代码
            'thirdPart' => array(
                'name' => 'Third-party Plug',
                'icon' => 'icon-cloud',
            ),
        ),
    );

    public function action_index(){
        $this->action_site();
    }

    /**
     * 站点设置
     */
    public function action_site(){
        $this->config_view('site');
    }

    /**
     * 水印设置
     */
    public function action_watermark(){
        $watermark = Watermark::instance();
        $this->main = View::factory('admin/setting/watermark')->set('water_position', $watermark->get_position());
        $this->main->watermark = $watermark->get_config();

        //保存数据
        if (HTTP_Request::POST == $this->request->method()){

            $watermark->set_config($this->request->post());
            $this->set_status('success');
        }
    }

    public function action_previewmark(){

        Watermark::instance()->preview($this->response);

    }

    /**
     * 第三方代码
     */
    public function action_thirdpart(){
        $this->config_view('thirdpart');
    }

    private function config_view($view){
        $this->main = View::factory('admin/setting/'.$view);
        $dataConfig = new Config_Database_Writer();
        Kohana::$config->attach($dataConfig);

        //加载或者创建一个新分组
        $config = Kohana::$config->load($view);
        $this->main->config = $config;

        //保存数据
        if (HTTP_Request::POST == $this->request->method()){
            foreach ($this->request->post() as $key=>$value){
                $config[$key] = $value;
            }

            $this->set_status('success');
        }

        Kohana::$config->detach($dataConfig);
    }
}
