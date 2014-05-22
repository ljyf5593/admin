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
     * 获取当前角色下的用户列表
     */
    public function action_users() {
        $id = intval($this->request->param('id'));
        if ($id > 0) {
            $this->main = View::factory('/admin/role/users');

            $model = ORM::factory('Role', $id);
            $user_model = $model->users;
            $pagination = new Pagination(array(
                'total_items' => $user_model->reset(FALSE)->count_all(),
                'view' => 'pagination/admin',
            ));
            $list_row = array(
                'id' => array(
                    'comment' => 'ID',
                ),
                'username' => array(
                    'comment' => '用户名',
                ),
                'realname' => array(
                    'comment' => '真实名称',
                ),
            );

            // 获取排序相关参数
            list($order, $by) = $this->get_order_param('dateline');
            if($order AND isset($list_row[$order])){
                $model->order_by($order, $by);
            }
            $this->main->set(array(
                'model_list' => $user_model->limit($pagination->items_per_page)->offset($pagination->offset)->find_all(),
                'list_row' => $list_row,
                'pagination' => $pagination,
                'order' => $order,
                'by' => $by,
            ));
        } else {
            $this->set_status('error', 'Parameter is not legitimate');
        }
    }

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
            $role = ORM::factory('Role', $id);

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
		$this->main->sys_roles = array('login');
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
