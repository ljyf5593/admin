<?php
/**
 * 用户Model
 *
 * @author Jie.Liu (ljyf5593@gmail.com)
 * @Id $Id: user.php 48 2012-07-16 06:53:18Z Jie.Liu $
 */
class Model_User extends Model_Auth_User{

    const TEACH_FULL = 1; // 专职
    const TEACH_PART = 2; // 兼职

    const GENDER_MALE = 1; // 男
    const GENDER_FEMALE = 2; // 女

    const AVATAR_DIR = 'avatar';
    
    /**
     * 关联个人信息
     * @var array
     */
    protected $_has_one = array(
        'user_profile' => array(),
    );

    protected $_has_many = array(
        'user_tokens' => array('model' => 'user_token'),
        'roles'       => array('model' => 'role', 'through' => 'roles_users'),
        'coaches' => array(),
        'articles' => array(),
    );

    protected $_created_column = array(
        'column' => 'regdate',
        'format' => TRUE,
    );
    
    protected $_search_row = array( 'id', 'username', 'email' );
    
    protected $_list_row = array( 'id',	'active', 'username', 'email', 'regdate', 'logins',	'last_login' );
    
    /**
     * 批量操作动作
     * @var array
     */
    protected $_batch_operation = array(
        'lock' => array(
            'style' => 'warning',
            'icon' => 'lock',
            'name' => 'Lock',
        ),
        'unlock' => array(
            'style' => 'success',
            'icon' => 'unlock',
            'name' => 'Unlock',
        ),
    );

	/**
	 * 获取最新的token
	 */
	public function get_token(){
		return $this->user_tokens->order_by('expires', 'DESC')->find()->token;
	}

    /**
     * 用户性别
     * @var array
     */
    public static $gender_arr = array(
        self::GENDER_MALE => '男',
        self::GENDER_FEMALE => '女',
    );

    /**
     * 教练身份
     * @var array
     */
    public static $teach_type = array(
        self::TEACH_FULL => '专职',
        self::TEACH_PART => '兼职',
    );

    /**
     * 判断用户名是否存在
     * @version 2011-11-18 上午11:49:39 Jie.Liu
     * @param string $username
     */
    public function username_vailable($username){
        return ORM::factory('user', array('username'=>$username))->loaded();
    }
    
    /**
     * 锁定用户
     * @version 2011-11-15 下午05:02:50 Jie.Liu
     */
    public function lock(){
        $this->active = 0;
        $this->save();
    }
    
    /**
     * 解除锁定
     * @version 2011-11-15 下午05:10:40 Jie.Liu
     */
    public function unlock(){
        $this->active = 1;
        $this->save();
    }
    
    /**
     * 更新用户角色
     * @param array $roles
     */
    public function update_roles($roles){
        //首先移除该用户的所有权限
        $this->remove('roles');
        //如果角色不为空则添加
        if (is_array($roles)){
            foreach ($roles as $key => $value){
                $this->add('roles', $key);
            }
        }
    }

    /**
     * 获取当前用户的权限
     * 如果是超级管理员，则直接返回
     */
    public function get_permissions(){
        $Administrator = FALSE;
        $user_permissions = $role_permissions = array();
        //首先获取他的所有角色
        $roles = $this->roles->find_all();
        foreach($roles as $role){
            if($role->name == 'Administrator'){
                $Administrator = TRUE;
            }
            $role_perms = unserialize($role->permissions);
            if(is_array($role_perms) AND !empty($role_perms)){
                foreach($role_perms as $perm){
                    $role_permissions[$perm] = $perm;
                }
            }
        }

        //超级管理员有权限、工具的权限
        if($Administrator){
            $user_permissions['permission'] = 'permission';
        }

        $user_permissions = array_merge($user_permissions, $role_permissions);

        return $user_permissions;
    }

    public function teach_type_show(){
        return Form::select('teach_type', array(
            self::TEACH_FULL => '专职',
            self::TEACH_PART => '兼职',
        ), $this->teach_type, array('id' => 'teach_type'));
    }

    public function gender_show(){
        return Form::select('gender', array(
            self::GENDER_MALE => '男',
            self::GENDER_FEMALE => '女',
        ), $this->gender, array('id' => 'gender'));
    }

    public function birthday_show(){
        $max_age = 150;
        $current_year = date('Y');
        $year_options = range($current_year-$max_age, $current_year);
        $month_options = range(1, 12);
        $day_options = range(1, 31);
        return '<div class="input-append">'.
            Form::select('birthday[year]', array_combine($year_options, $year_options)
            , date('Y', $this->birthday), array('class'=>'input-mini')).
            '<div class="add-on">年</div>'.
            Form::select('birthday[month]', array_combine($month_options, $month_options)
            , date('m', $this->birthday), array('class'=>'input-mini')).
            '<div class="add-on">月</div>'.
            Form::select('birthday[day]', array_combine($day_options, $day_options)
            , date('d', $this->birthday), array('class'=>'input-mini')).
            '<div class="add-on">日</div></div>';
    }
}