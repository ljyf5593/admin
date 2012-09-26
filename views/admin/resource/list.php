<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="panel">
	<div class="panel-header">
		<h2><i class="icon-list-alt icon-blue"></i><?php echo __($model_name).__('List')?></h2>
		<div class="actions-bar">
			<div class="btn-group pull-right">
				<a class="btn btn-success btn-mini">Actions</a>
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

	<form action="<?php echo Route::url('admin/global', array('controller'=>$model_name, 'action'=>'batch'))?>" class="ajaxform" method="post">
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
				<td class="selections"><input class="selection" type="checkbox" name="ids[]" value="<?php echo $model->pk();?>"></td>
				<?php foreach($list_row as $key => $value):?>
				<td>
				<?php
					switch($key){
						case 'dateline':

						case 'update':
							echo date(Date::$timestamp_format, $model->$key);
							break;

						case 'attachment':
							echo $model->$key.'&nbsp;<a target="_blank" href="'.URL::site('/upload/'.$model->$key).'"><img height="100" class="preview" src="'.URL::site('/upload/'.$model->$key).'"><i class="icon-picture icon-green icon-large"></i></a>';
							break;

						default :
							echo  $model->$key;
							break;
					}
				?>
				</td>
				<?php endforeach;?>

				<td>
					<a class="btn btn-mini btn-info ajax" href="<?php echo Route::url('admin', array('controller'=>Request::$current->controller(), 'action' => 'edit', 'id'=>$model->pk()));?>"><i class="icon-edit"></i><?php echo __('Edit')?></a>
					<a class="btn btn-mini btn-danger ajax" href="<?php echo Route::url('admin', array('controller'=>Request::$current->controller(), 'action'=>'delete','id'=>$model->pk()));?>" data-confirm="你确定要删除该附件吗？"><i class="icon-trash"></i><?php echo __('Delete')?></a>
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