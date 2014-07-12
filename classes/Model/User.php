<?php
/**
 * 用户Model
 *
 * @author Jie.Liu (ljyf5593@gmail.com)
 * @Id $Id: user.php 238 2013-03-20 09:06:49Z Jie.Liu $
 */
class Model_User extends Model_Auth_User {

    const GENDER_MALE = 1; // 男
    const GENDER_FEMALE = 2; // 女

    const ACTIVE_ENABLE = 1; // 正常
    const ACTIVE_DISABLE = 0; // 锁定

    // 邮箱认证
    const EMAIL_UNVERIFIED = 0; // 为验证
    const EMAIL_VERIFIED = 1; // 已验证

    const AVATAR_DIR = 'avatar';

    protected $_has_many = array(
        'user_tokens' => array('model' => 'User_Token'),
        'roles'       => array('model' => 'Role', 'through' => 'roles_users'),
    );

    protected $_created_column = array(
        'column' => 'dateline',
        'format' => TRUE,
    );

    protected $_search_row = array( 'id', 'username', 'email' );

    protected $_list_row = array( 'id', 'active', 'username', 'email', 'dateline', 'logins', 'last_login' );

    protected $_list_row = array( 'id',	'active', 'username', 'email', 'dateline', 'logins',	'last_login' );

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

    // 日期显示列
    public $date_row = array('birthday');
    // 时间显示列
    public $time_row = array('dateline', 'last_login');

    public function filters() {
        return array(
            'dateline' => array(
                array('strtotime'),
            ),
            'birthday' => array(
                array('strtotime'),
            ),
            'last_login' => array(
                array('strtotime'),
            ),
        ) + parent::filters();
    }

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
     * 判断用户名是否存在
     * @version 2011-11-18 上午11:49:39 Jie.Liu
     * @param string $username
     */
    public function username_vailable($username){
        return ORM::factory('User', array('username'=>$username))->loaded();
    }

    /**
     * 锁定用户
     * @version 2011-11-15 下午05:02:50 Jie.Liu
     */
    public function lock(){
        $this->active = self::ACTIVE_DISABLE;
        $this->save();
    }

    /**
     * 解除锁定
     * @version 2011-11-15 下午05:10:40 Jie.Liu
     */
    public function unlock(){
        $this->active = self::ACTIVE_ENABLE;
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

    public function get_active() {
        return ($this->active == self::ACTIVE_ENABLE)?'<a class="ajax" href="'.Route::url('admin/global', array('controller' => $this->_object_name, 'action' => 'single', 'operation' => 'lock', 'id' => $this->pk())).'"><span class="label label-success"><i class="icon-eye-open icon-large"></i>'.__('Unlock').'</span></a>':'<a class="ajax" href="'.Route::url('admin/global', array('controller' => $this->_object_name, 'action' => 'single', 'operation' => 'unlock', 'id' => $this->pk())).'"><span class="label label-warning"><i class="icon-eye-close icon-large"></i>'.__('Lock').'</span></a>';
    }

    /**
     * 性别展示
     * @return string
     */
    public function gender_show(){
        return Form::select('gender', array(
            self::GENDER_MALE => '男',
            self::GENDER_FEMALE => '女',
        ), $this->gender, array('id' => 'gender', 'class' => 'form-control'));
    }

    /**
     * 邮箱验证状态信息
     */
    public function verify_email_show() {
        return Form::select('verify_email', array(
                self::EMAIL_VERIFIED => '已验证',
                self::EMAIL_UNVERIFIED => '未验证',
            ), $this->verify_email, array('id' => 'gender', 'class' => 'form-control'));
    }

    /**
     * 生日显示
     * @return string
     */
    public function birthday_show(){
        $max_age = 150;
        $current_year = date('Y');
        $year_options = range($current_year-$max_age, $current_year);
        $month_options = range(1, 12);
        $day_options = range(1, 31);
        return Form::select('birthday[year]', array_combine($year_options, $year_options)
            , date('Y', $this->birthday), array('class'=>'input-small')).
            '<span>年</span>'.
            Form::select('birthday[month]', array_combine($month_options, $month_options)
            , date('m', $this->birthday), array('class'=>'input-mini')).
            '<span>月</span>'.
            Form::select('birthday[day]', array_combine($day_options, $day_options)
            , date('d', $this->birthday), array('class'=>'input-mini')).
            '<span>日</span>';
    }
}