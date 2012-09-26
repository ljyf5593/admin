<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="panel">
	<div class="panel-header">
		<h2><i class="icon-cogs icon-blue"></i><?php echo __('Backup');?></h2>
	</div>
	<div class="panel-content">

		<form class="form-horizontal" action="<?php echo Route::url('admin', array('controller' => 'tool', 'action' => 'backup'));?>" method="post">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<td class="selections select-all"><input type="checkbox" class="selection select-all"></td>
						<?php foreach($options as $option):?>
						<td><?php echo __($option);?></td>
						<?php endforeach;?>
					</tr>
				</thead>
				<tbody>
					<?php $table_pre_len = strlen($table_prefix);?>
					<?php foreach($tables as $table):?>
					<?php $table_name = substr($table['Name'], $table_pre_len);?>
					<tr>
						<td class="selections"><input class="selection" type="checkbox" name="tables[]" value="<?php echo $table_name;?>"></td>
						<?php foreach($options as $option):?>
						<td><?php echo $table[$option];?></td>
						<?php endforeach;?>
					</tr>
					<?php endforeach;?>
				</tbody>
			</table>
			<div class="well control-group">
				<label  class="control-label"><?php echo __('Backup Location');?></label>
				<div class="controls">
					<label class="radio inline">
						<?php echo Form::radio('type', 'online'); echo __('Online');?>
					</label>
					<label class="radio inline">
						<?php echo Form::radio('type', 'local'); echo __('Local');?>
					</label>
					<button class="btn btn-primary pull-right"><i class="icon-share icon-white"></i><?php echo __('Backup');?></button>
				</div>
			</div>

		</form>
	</div>
</div><!--/panel-->