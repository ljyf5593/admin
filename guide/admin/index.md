# Admin
	该模块为一个功能简单的后台管理模块，包含了基本的CRUD操作，和站点配置功能。

## 特点
*	最少的代码完成CRUD操作(读取数据库注释及字段类型，约定大于配置)
*	列表显示项定制及显示方式定制，排序功能
*	搜索显示项定制及搜索方式定制
*	编辑项定制
*	单项操作定制
*	批量操作定制
*	基本的JS控件(时间控件、富文本编辑器、弹出框)
*	其他功能(权限控制、数据备份、运行日志)
*	强大的文件层级加载机制
	*	实现模块之间透明扩展和模块内部的自给自足(e: 在comment模块扩展admin)
	*	如果你对这些都不满意可以自己完全自定义，而不影响其他模块

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

[!!] 以上模块是必须开启的,Admin依赖以上模块，另外，admin模块必须在ORM模块之前开启，其中Media模块可以[点击这里下载](https://github.com/ljyf5593/media)