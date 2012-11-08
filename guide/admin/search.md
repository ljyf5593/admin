# 列表页添加搜索选项

例如为评论添加搜索审核通过功能

## 在对应的Model中定义"_search_row"属性
model/comment.php
 
	protected $_search_row = array(
		'username', 'state'
	);
	
如果想要针对某一列的搜索使用下拉菜单的形式，则可以在model中定义方法
model/comment.php

	/**
	 * 举报状态下拉菜单
	 */
	public function state_search(){
		return Form::select('state', self::$STATE);
	}

[!!] 这里的方法名称必须要和上面的定义的key值一致，后面加上"_search"