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

# 列表页定制化某一项

如果想要针对某一列的搜索使用下拉菜单的形式，则可以在model中定义方法
model/comment.php

	/**
	 * 审核评论
	 */
	public function audited() {
		$this->status = self::audited;
		$this->save();
	}

	/**
	 * 取消审核评论
	 */
	public function unaudited() {
		$this->status = self::unaudited;
		$this->save();
	}

	/**
	 * 后台评论列表操作评论状态
	 */
	public function get_status(){
		return ($this->status == self::audited) ?
			'<a class="ajax" href="'.Route::url('admin/global', array('controller' => 'comment', 'action' => 'single', 'operation' => 'unaudited', 'id' => $this->pk())).'"><span class="label label-success"><i class="icon-eye-open icon-large"></i>'.__('audited').'</span></a>' :
			'<a class="ajax" href="'.Route::url('admin/global', array('controller' => 'comment', 'action' => 'single', 'operation' => 'audited', 'id' => $this->pk())).'"><span class="label label-warning"><i class="icon-eye-close icon-large"></i>'.__('unaudited').'</span></a>';
	}