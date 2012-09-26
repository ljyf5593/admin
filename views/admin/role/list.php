<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="panel">
	<div class="panel-header">
		<h2><i class="icon-list-alt icon-blue"></i><?php echo __($model_name).__('List')?></h2>
		<div class="actions-bar">
			<div class="btn-group pull-right">
				<?php echo HTML::anchor(Route::url('admin', array('controller' => Request::$current->controller(), 'action'=>'create')), '<i class="icon-plus"></i>'.__('Create').__($model_name), array('class' => 'btn btn-success btn-mini ajax'));?>
				<a class="btn btn-success btn-mini dropdown-toggle" data-toggle="dropdown">
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<li>
						<?php echo HTML::anchor(Route::url('admin', array('controller' => Request::$current->controller(), 'action'=>'create')), '<i class="icon-plus"></i>'.__('Create').__($model_name), array('class' => 'ajax'));?>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<div class="panel-content">
	<!-- //Search form -->
	<?php echo View::factory('admin/search')->bind('search', $search);?>

	<form action="<?php echo Route::url('admin/global', array('controller'=>'permissions', 'action'=>'batch'))?>" class="ajaxform" method="post">
		<table class="table table-bordered table-striped">
			<thead>
			<tr>
				<td class="selections select-all"><input type="checkbox" value="1" class="selection select-all"></td>
				<?php foreach($list_row as $key => $value):?>
				<th><?php echo $value['comment'];?></th>
				<?php endforeach;?>
				<th><?php echo __('Operations')?></th>
			</tr>
			</thead>
			<tbody>
			<?php foreach($model_list as $model):?>
			<tr>
				<td class="selections"><input class="selection" type="checkbox" name="ids[]" value="<?php echo $model->pk();?>" <?php echo in_array($model->name, $sys_roles)?'disabled="disabled"':''?>></td>
				<?php foreach($list_row as $key => $value):?>
				<td><?php echo  $model->$key;?></td>
				<?php endforeach;?>

				<td>
				<?php
					echo HTML::anchor(Route::url('admin', array('controller'=>Request::$current->controller(), 'action' => 'edit', 'id'=>$model->pk()), TRUE), '<i class="icon-edit"></i>'.__('Edit'), array('class' => 'btn btn-mini btn-info ajax'))."\n";
					echo HTML::anchor(Route::url('admin', array('controller'=>Request::$current->controller(), 'action' => 'perm', 'id'=>$model->pk()), TRUE), '<i class="icon-check"></i>'.__('Permission'), array('class' => 'btn btn-mini btn-warning ajax'))."\n";
					if(!in_array($model->name, $sys_roles)){
						echo HTML::anchor(Route::url('admin', array('controller'=>Request::$current->controller(), 'action' => 'delete', 'id'=>$model->pk()), TRUE), '<i class="icon-trash"></i>'.__('Delete'), array('class' => 'btn btn-mini btn-danger ajax'))."\n";
					}
				?>
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
		</div>
	</form>
	<?php echo $pagination->render(); ?>
	</div>
</div>