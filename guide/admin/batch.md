# 添加批量操作特性

## 在前台页面上添加批量选中功能
### 在触发全选的input上添加"select_all"的class,例如:

    <td><input type="checkbox" class="select-all"></td>

### 添加当触发全选时，那些元素将被选中，需要被选中的元素需添加"selection"的class,例如:

    <td><input class="selection" type="checkbox" name="ids[]" value="<?php echo $model->pk();?>"></td>

例如为评论添加批量审核通过功能

## 在对应的Model中定义"_batch_operation"属性
model/comment.php
 
	protected $_batch_operation = array(
		'audit' => array(
			'style' => 'info',  // 定义批量操作按钮的风格  默认为 warning
			'name' => 'audit', // 定义批量操作的名称  默认为批量操作动作的key值
			'icon' => 'exclamation-sign', // 定义批量操作按钮的图标， 默认为exclamation-sign,注意这里不用加icon-
		),
	);
	
## 在对应的Model中定义批量操作的方法
model/comment.php

	public function audit() {  
		$this->status = 2;
		$this->save();
	}

[!!] 这里的方法名称必须要和上面的定义的key值一致