<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * 用户角色Model
 *
 * @author Jie.Liu (ljyf5593@gmail.com)
 * @Id $Id: role.php 33 2012-06-29 07:32:34Z Jie.Liu $
 */
class Model_Role extends Model_Auth_Role {

	/**
	 * 列表显示项
	 **/
	protected $_list_row = array('id', 'name', 'description');

	protected $_search_row = array('id', 'name');

} // End Role Model