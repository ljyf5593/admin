# 后台模板效果调用

## 调用时间控件
	HTML代码如下:
	
	<input type="text" class="input-large date" name="date" id="date" value="<?php echo $date;?>">
	<span class="add-on date" target="date">
		<i class="icon-calendar icon-blue"></i>
	</span>
	
	说明:
	触发时间控件弹出的jQuery选择器："$('span.date')", 触发的时间控件想要作用到的form元素在"target"属性中定义，
	例如上例中的代码就是将时间控件作用到"id"为"date"的"input"元素上
	选择器"$('span.date')" 将调用日期控件
	选择器"$('span.datetime')" 将调用日期时间控件

## 调用弹出确认框
	HTML代码如下:

    <a class="btn btn-mini btn-danger ajax confirm" href="<?php echo Route::url('admin', array('controller'=>$controller, 'action'=>'delete','id'=>$model->pk()));?>" data-confirm="<?php echo __('Are you sure to delete this object')?>?"><i class="icon-trash"></i><?php echo __('Delete')?></a>

    说明:
    触发弹出框的机制是，该元素必须是class中有"ajax"的a标签，且该a标签有自定义数据"data-confirm",该自定义数据的值为弹出框弹出的信息内容.