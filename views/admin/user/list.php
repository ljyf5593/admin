<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="panel">
	<div class="panel-header">
		<h2><i class="icon-list-alt icon-blue"></i><?php echo __($model_name).__('List')?></h2>
		<div class="actions-bar">
			<div class="btn-group pull-right">
				<a class="btn btn-success btn-mini ajax" href="<?php echo Route::url('admin', array('controller' => $model_name, 'action'=>'create'))?>"><i class="icon-plus"></i><?php echo __('Create').__('User');?></a>
				<a class="btn btn-success btn-mini dropdown-toggle" data-toggle="dropdown">
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<li><a class="ajax" href="<?php echo Route::url('admin', array('controller' => $model_name, 'action'=>'create'))?>"><i class="icon-plus"></i><?php echo __('Create').__('User');?></a></li>
				</ul>
			</div>
		</div>
	</div>

	<div class="panel-content">
	<?php echo View::factory('admin/search')->bind('search', $search);?>

	<form action="<?php echo Route::url('admin/global', array('controller'=>$model_name, 'action'=>'batch'))?>" class="ajaxform" method="post">
		<table class="table table-bordered table-striped">
			<thead>
			<tr>
				<td class="selections select-all"><input type="checkbox" class="selection select-all"></td>
				<?php foreach($list_row as $key => $value):?>
				<th><?php echo $value['comment'];?></th>
				<?php endforeach;?>
				<th><?php echo __('Operations')?></th>
			</tr>
			</thead>
			<tbody>
			<?php foreach($model_list as $model):?>
			<tr>
				<td class="selections"><input class="selection" type="checkbox" name="ids[]" value="<?php echo $model->pk();?>"></td>
				<?php foreach($list_row as $key => $value):?>
				<td>
				<?php
					switch($key){
						case 'regdate':

						case 'last_login':
							echo date(Date::$timestamp_format, $model->$key);
							break;

						case 'active':
							echo ($model->$key === '1')?'<a class="ajax" href="'.Route::url('admin/global', array('controller' => $model_name, 'action' => 'single', 'operation' => 'lock', 'id' => $model->pk())).'"><span class="label label-success"><i class="icon-unlock"></i>'.__('Unlock').'</span></a>':'<a class="ajax" href="'.Route::url('admin/global', array('controller' => $model_name, 'action' => 'single', 'operation' => 'unlock', 'id' => $model->pk())).'"><span class="label label-warning"><i class="icon-lock"></i>'.__('Lock').'</span></a>';
							break;
						default :
							echo $model->$key;
					}
				?>
				</td>
				<?php endforeach;?>

				<td>
					<a class="btn btn-mini btn-info ajax" href="<?php echo Route::url('admin', array('controller'=>$model_name, 'action' => 'edit', 'id'=>$model->pk()));?>"><i class="icon-edit"></i><?php echo __('Edit')?></a>
					<a class="btn btn-mini btn-danger ajax" href="<?php echo Route::url('admin', array('controller'=>$model_name, 'action'=>'delete','id'=>$model->pk()));?>"><i class="icon-trash"></i><?php echo __('Delete')?></a>
				</td>
			</tr>
			<?php endforeach;?>

			</tbody>
		</table>

		<div class="well">
			<?php if(!empty($batch_operations)):?>
			<?php foreach($batch_operations as $batch_action => $batch):?>
				<a class="btn btn-small btn-<?php echo $batch['style'];?> batch" rel="<?php echo $batch_action?>"><i class="icon-<?php echo $batch['icon']?>"></i> <?php echo __($batch['name']).' '.__('selected');?></a>
				<?php endforeach;?>
			<?php endif;?>
			<a class="btn btn-small btn-danger batch" rel="delete"><i class="icon-remove"></i><?php echo __('Remove').' '.__('selected')?></a>
			<div class="btn-group pull-right">
				<a class="btn btn-primary"><i class="icon-share icon-white"></i> Change status to:</a>
				<a data-toggle="dropdown" class="btn btn-primary dropdown-toggle">
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<li><a href="#"><i class="icon-star"></i> Administrator</a></li>
					<li><a href="#"><i class="icon-user"></i> User</a></li>
					<li><a href="#"><i class="icon-edit"></i> Editor</a></li>
				</ul>
			</div>
		</div>
	</form>
	<?php echo $pagination->render(); ?>

	</div>
</div>