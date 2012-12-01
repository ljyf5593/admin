# Admin
	该模块为一个功能简单的后台管理模块，包含了基本的CRUD操作，和站点配置功能。

## 特点
*	模块化，模块内自给自足

## 开始使用

	在使用这个模块之前我们必须先开启这个模块

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

[!!] 以上模块是必须开启的,Admin依赖以上模块，另外，admin模块必须在ORM模块之前开启，其中Media模块可以[点击这里下载](https://github.com/ljyf5593/Kohana-media)