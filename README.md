admin
=====

admin module for kohana3.3

demo: <a target="_blank" href="http://comasa.pagodabox.com">comasa.pagodabox.com/admin</a>
user: demo
password: demodemo

## Requirements
[media](https://github.com/ljyf5593/media)
[pagination](https://github.com/ljyf5593/pagination)
auth
database
image
orm

## Install

1. set the module enable

	Kohana::modules(array(
		...
		'admin' => MODPATH . 'admin',
		'media' => MODPATH . 'media',
		'auth' => MODPATH . 'auth', // Basic authentication
		'database' => MODPATH . 'database', // Database access
		'image' => MODPATH . 'image', // Image manipulation
		'orm' => MODPATH . 'orm', // Object Relationship Mapping
		'pagination' => MODPATH . 'pagination', // 启用分页
		...
	));

2. config the database info

3. import modules/admin/data/admin-mysql.sql

4. the default admin user is

	username: administrator
	password: 123698745

login

![Image](https://raw.githubusercontent.com/ljyf5593/admin/master/data/login.jpg)

user list

![Image](https://raw.githubusercontent.com/ljyf5593/admin/master/data/user_list.jpg)

user edit

![Image](https://raw.githubusercontent.com/ljyf5593/admin/master/data/user_edit.jpg)

setting

![Image](https://raw.githubusercontent.com/ljyf5593/admin/master/data/setting.jpg)
