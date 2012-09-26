<?php defined('SYSPATH') or die('No direct script access.');?>

<!--//列表搜索表单 -->
<form class="well form-inline ajaxform" action="<?php echo Route::url('admin/list', array('controller'=>Request::$current->controller(), 'action'=>'list'));?>" method="get">
	<?php
		$model = $search['model'];
		$search_row = $search['search_row'];
		foreach($search_row as $key => $value){
			$func = $key.'_showselect';
			$cut_len = UTF8::strpos($value['comment'], '(');
			$comment = $cut_len ? UTF8::substr($value['comment'], 0, $cut_len):$value['comment'];
			if(method_exists($model, $func)){
				echo '<label class="search-label">'.$comment.'</label>'.$model->$func();

			}elseif($key == 'id'){
                echo '<label class="search-label">'.$comment.'</label>'.Form::input($key, NULL, array('class' => 'input-mini', 'placeholder' => $value['comment']));
            }else{ // 判断是否可以模糊查询，是则显示一个特殊标识
				echo '<label class="search-label">'.$comment.( ORM::isFuzzyQuery($value) ? ' <i class="icon-asterisk popover-help-top" title="'.__('Help Text').'" data-content="'.__('Fuzzy query').'"></i>':'').'</label>'.Form::input($key, NULL, array('class' => 'input-medium', 'placeholder' => $value['comment']));
			}
		}
	?>
	<button type="submit" class="btn btn-primary">
		<i class="icon-search"></i>
		<?php echo __('Search');?>
	</button>
</form>