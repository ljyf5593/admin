# 添加后台站点地图

后台导航菜单是通过在admin.php配置文件中配置的，配置文件内容如下
"config/admin.php"

	<?php defined('SYSPATH') or die('No direct access allowed.');

	return array(
		'top_nav' => array( // 顶部菜单
			// 连接管理
			'link' => array(),
			
			'test' => array(
				'sub_nav' => array( // 顶部菜单下可以有子菜单
					'test1' => array()
				),
			),
		),

		'side_nav' => array( // 侧边菜单
			// 教学文章
			'article' => array( // key值表示了控制器
				'icon' => 'icon-leaf', // 菜单项的图标
				'name' => 'article', // 菜单项的名称，如果该项没有，则默认使用key作为名称
			)
		),
	);
	
# 控制器内添加菜单
想为某一个单独的控制器添加多个菜单，需要在该控制器内定义这个菜单,例如:
"classes/controller/admin/article"

	class Controller_Admin_Article extends Controller_Admin_Crud {

		protected $top_actions = array(
			'menu' => array(
				'category' => array( // key值表示该值在控制器中的action名称
					'name' => 'Category', // 菜单的名称
					'icon' => 'icon-list-ul', // 菜单项的图标
				),
			),
			'dropdown' => array(

			),
		);
		
		...
		
