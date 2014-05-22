<?php
	defined('SYSPATH') or die('No direct script access.');
	$controller = Request::$current->controller();
?>
<div class="panel">
	<div class="panel-header">
		<h2><i class="icon-list-alt icon-blue"></i><?php echo __($model_name).__('List')?></h2>
		<div class="actions-bar">
			<div class="btn-group pull-right">
				<?php echo HTML::anchor(Route::url('admin', array('controller' => $controller, 'action'=>'create')), '<i class="icon-plus"></i>'.__('Create').__($model_name), array('class' => 'btn btn-success btn-mini ajax-modal'));?>
				<a class="btn btn-success btn-mini dropdown-toggle" data-toggle="dropdown">
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<li>
						<?php echo HTML::anchor(Route::url('admin', array('controller' => $controller, 'action'=>'create')), '<i class="icon-plus"></i>'.__('Create').__($model_name), array('class' => 'ajax-modal'));?>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<div class="panel-content">
	<!-- //Search form -->
	<?php echo View::factory('admin/common/search')->bind('search', $search);?>

	<form action="<?php echo Route::url('admin/global', array('controller'=>'permissions', 'action'=>'batch'))?>" class="ajaxform" method="post">
		<table class="table table-bordered table-striped">
			<!-- //表格头 -->
			<?php include Kohana::find_file('views', 'admin/common/thead')?>

			<tbody>
			<?php foreach($model_list as $model):?>
			<tr>
				<td><input class="selection" type="checkbox" name="ids[]" value="<?php echo $model->pk();?>" <?php echo in_array($model->name, $sys_roles)?'disabled="disabled"':''?>></td>
				<?php foreach($list_row as $key => $value):?>
				<td><?php echo  $model->$key;?></td>
				<?php endforeach;?>

				<td>
				<?php
                echo HTML::anchor(Route::url('admin', array('controller'=>$controller, 'action' => 'users', 'id'=>$model->pk()), TRUE), '<i class="icon-edit"></i>'.__('User'), array('class' => 'btn btn-mini btn-success ajax'))."\n";
					echo HTML::anchor(Route::url('admin', array('controller'=>$controller, 'action' => 'edit', 'id'=>$model->pk()), TRUE), '<i class="icon-edit"></i>'.__('Edit'), array('class' => 'btn btn-mini btn-info ajax-modal'))."\n";
					if($model->name != ADMINISTRATOR){
						echo HTML::anchor(Route::url('admin', array('controller'=>$controller, 'action' => 'perm', 'id'=>$model->pk()), TRUE), '<i class="icon-check"></i>'.__('Permission'), array('class' => 'btn btn-mini btn-warning ajax'))."\n";
					}
					if(!in_array($model->name, $sys_roles)){
						echo HTML::anchor(Route::url('admin', array('controller'=>$controller, 'action' => 'delete', 'id'=>$model->pk()), TRUE), '<i class="icon-trash"></i>'.__('Delete'), array('class' => 'btn btn-mini btn-danger ajax'))."\n";
					}
				?>
				</td>

			</tr>
			<?php endforeach;?>

			</tbody>
		</table>
		<!-- // 批量操作 -->
		<?php include Kohana::find_file('views', 'admin/common/batch')?>
	</form>
	<?php echo $pagination->render(); ?>
	</div>
</div>