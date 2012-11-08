<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 权限管理控制器
 *
 * @author  Jie.Liu (ljyf5593@gmail.com)
 * @Id $Id: permission.php 74 2012-09-02 08:43:11Z jie $
 */
class Controller_Admin_Permission extends Controller_Admin_Crud {

    public $_model = 'role';

    /**
     * 用户权限编辑
     */
    public function action_perm(){
        $id = intval($this->request->param('id'));
        if($id > 0){
            $this->main = View::factory('/admin/role/perm');

            //首先获取所有的控制器
            $this->main->perms = $this->get_all_perms();

            //获取当前编辑的用户角色
            $role = ORM::factory('role', $id);

            if(HTTP_Request::POST == $this->request->method()) {

                $role->permissions = serialize($this->request->post('perm'));
                $role->save();

                $this->set_status('success');

                // 权限保存成功后应该刷新当前页面，使得权限数据立即在也没上生效
                $this->location($this->request->uri(), 2);
            }

            $role_perms = array();
            $role->permissions = unserialize($role->permissions);
            if(is_array($role->permissions)){
                $role_perms = $role->permissions;
            }

            $this->main->role = $role;
            $this->main->role_perms = $role_perms;

        } else {
            $this->set_status('error', 'Parameter is not legitimate');
        }
    }

	public function after(){
		$this->main->sys_roles = array('login', ADMINISTRATOR);
		parent::after();
	}

    private function get_all_perms(){
        $config = Kohana::$config->load('admin');

		// 在权限分配中不显示permission权限，该权限超级管理员自动拥有
		$top_nav = $config['top_nav'];
		unset($top_nav['permission']);

        return Arr::merge($top_nav, $config['side_nav']);
    }
}
