<?php defined('SYSPATH') or die('No direct script access.');?>
<thead>
<tr>
    <td><input type="checkbox" class="select-all"></td>
	<?php foreach($list_row as $key => $value):?>
    <th><a class="ajax" href="<?php echo Route::url('admin/list', array(
		'controller' => $controller,
		'action' => 'list',
		'page' => $pagination->current_page,
		'order' => $key,
		'by' => ($by === 'desc') ? 'asc' : 'desc',
	))?>">
		<?php
		echo $value['comment'];
		$by_icon = array(
			'desc' => 'icon-sort-down',
			'asc' => 'icon-sort-up'
		);
		if($order === $key){
			echo '<i class="'.$by_icon[$by].'"></i>';
		} else {
			echo '<i class="icon-sort"></i>';
		}
		?></a></th>
	<?php endforeach;?>
    <th><?php echo __('Operations')?></th>
</tr>
</thead>