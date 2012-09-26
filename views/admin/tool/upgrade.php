<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="panel">
	<div class="panel-header">
		<h2><i class="icon-cogs icon-blue"></i><?php echo __('Upgrade');?></h2>
	</div>
	<div class="panel-content">

		<form class="form-horizontal ajaxform" action="<?php echo Route::url('admin', array('controller' => 'tool', 'action' => 'Upgrade'));?>" method="post">

			<div class="control-group">
				<label  class="control-label"><?php echo __('SQL statements');?></label>
				<div class="controls">
					<textarea rows="5" class="span8" name="sql" placeholder="<?php echo __('Input').'SQL'?>"></textarea>
				</div>
			</div>

			<div class="well control-group">
				<label  class="control-label"><?php echo __('Query type');?></label>
				<div class="controls">
					<label class="radio inline">
						<?php echo Form::radio('type', 'select', true); echo __('SELECT');?>
					</label>
					<label class="radio inline">
						<?php echo Form::radio('type', 'update'); echo __('UPDATE');?>
					</label>
					<label class="radio inline">
						<?php echo Form::radio('type', 'delete'); echo __('DELETE');?>
					</label>
					<label class="radio inline">
						<?php echo Form::radio('type', 'insert'); echo __('INSERT');?>
					</label>
					<button class="btn btn-primary pull-right"><i class="icon-share icon-white"></i><?php echo __('Backup');?></button>
				</div>
			</div>

		</form>
	</div>
</div><!--/panel-->