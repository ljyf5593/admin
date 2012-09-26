<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="panel">
	<div class="panel-header">
		<h2><i class="icon-user-md icon-blue"></i><?php echo __('Role list')?></h2>
	</div>
	<div class="panel-content">
		<div class="row-fluid">
			<form class="ajaxform" method="post" action="<?php echo Route::url('admin', array('controller' => Request::$current->controller(), 'action' => 'perm', 'id' => $role->pk()))?>">
				<table class="table table-bordered table-striped">
				<thead>
				<tr>
					<th><?php echo __('Permission').__('Name')?></th><th><?php echo __('Permission').__('Discription')?></th><th><?php echo $role->name;?></th>
				</tr>
				</thead>
				<?php
					foreach($perms as $key => $value):
						$lang_key = __(ucfirst($key));
				?>
				<tr>
					<td><?php echo $lang_key;?></td>
					<td><?php echo Arr::get($value, 'discription', __('Manage').$lang_key);?></td>
					<td>
						<input type="checkbox" name="perm[]" value="<?php echo $key;?>" <?php echo in_array($key, $role_perms)?'checked="checked"':''?>>
					</td>
				</tr>
				<?php endforeach;?>
				</table>
				<div class="form-actions">
					<button class="btn btn-primary" type="submit"><?php echo __('Save');?></button>
					<button class="btn" type="reset"><?php echo __('Reset');?></button>
				</div>
			</form>
		</div>
	</div>
</div><!--/panel-->