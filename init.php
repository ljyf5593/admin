<?php defined('SYSPATH') or die('No direct script access.');

define('ADMIN_VERSION', '0.0.1');

// 超级管理员角色名称
define('ADMINISTRATOR', 'administrator');

/**
 * 初始化文件 包含路由信息
 *
 * @author  Jie.Liu (ljyf5593@gmail.com)
 * @Id $Id: init.php 52 2012-07-18 02:09:12Z Jie.Liu $
 */
Route::set('admin/auth', 'admin/<action>', array('action' => '(login|logout)'))
	->defaults(array(
	    'controller' => 'auth',
	));

/**
 * 全局操作
 */
Route::set('admin/global', 'admin/<controller>/<action>(/<operation>(/<id>))', array('action' => '(batch|single)', 'id' => '[0-9]+'))
	->defaults(array(
	'directory'  => 'admin',
));

Route::set('admin/list', 'admin/<controller>(/<action>(/<page>(/<order>(/<by>))))', array('action' => '(index|list|manage)', 'page' => '[0-9]+', 'by' => '(desc|asc)'))
	->defaults(array(
	'directory'  => 'admin',
	'controller' => 'home',
	'action'     => 'index',
	'page' => '1',
));

Route::set('admin', 'admin(/<controller>(/<action>(/<id>)))')
    ->defaults(array(
        'directory'  => 'admin',
        'controller' => 'home',
        'action'     => 'index',
    ));