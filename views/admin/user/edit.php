<?php defined('SYSPATH') or die('No direct script access.');?>
<?php
$input = __('input');
foreach($list_columns as $key=>$value):
switch($key){
	case 'id':
		$uneditable = $model->id?$model->id:__('uneditable');
		echo <<<HTML
<div id="{$key}-control-group" class="control-group">
	<label for="{$key}" class="control-label">{$value['comment']}</label>
	<div class="controls">
		<span class="input-xlarge uneditable-input">{$uneditable}</span>
	</div>
</div>
HTML;
		break;
	case 'active':
		$status = array(
			1 => __('Enable'),
			0 => __('Disable'),
		);
		$val = $model->$key;
		$is_recommended = ($val == 1);
		$status_1_checked = $status_0_checked = "";
		if($is_recommended){
			$class = 'alert-info';
			$status_1_checked = 'checked="checked"';
		} else {
			$class = 'alert';
			$status_0_checked = 'checked="checked"';
		}
		echo <<<HTML
<div id="{$key}-control-group" class="control-group {$class}">
	<label for="{$key}" class="control-label">
		<span class="add-on">
			{$value['comment']}
		</span>
	</label>
	<div class="controls">
		<label class="radio inline">
			<input type="radio" name="{$key}" value="1" {$status_1_checked}>{$status[1]}
			</label>
		<label class="radio inline">
			<input type="radio" name="{$key}" value="0" {$status_0_checked}>{$status[0]}
		</label>
		<span id="{$key}-label" class="label label-important hide"></span>
	</div>
</div>
HTML;
		break;
	case 'password':
		$icon = '<i class="icon-info-sign icon-red popover-help-top" title="'.__('Alert Text').'" data-content="'.__('Change your password, please fill in here, otherwise leave blank').'"></i>';
		echo <<<HTML
<div id="{$key}-control-group" class="control-group alert-danger">
	<label for="{$key}" class="control-label">
		{$value['comment']}
	</label>
	<div class="controls input-append">
		<input type="password" name="{$key}" value="" id="{$key}" class="input-xlarge" placeholder="{$input}{$value['comment']}">
		<span class="add-on">
			{$icon}
		</span>
		<span id="{$key}-label" class="label label-important hide"></span>
	</div>
</div>
<div id="password_confirm-control-group" class="control-group alert-danger">
	<label for="password_confirm" class="control-label">确认{$value['comment']}</label>
	<div class="controls">
		<input type="password" name="password_confirm" value="" id="password_confirm" class="input-xlarge" placeholder="确认{$value['comment']}">
		<span id="password_confirm-label" class="label label-important hide"></span>
	</div>
</div>
HTML;
		break;
	case 'dateline':

	case 'last_login':
		$val = date(Date::$timestamp_format, $model->$key);
		echo <<<HTML
<div id="{$key}-control-group" class="control-group">
	<label for="{$key}" class="control-label">{$value['comment']}</label>
	<div class="controls input-append">
		<input type="text" name="{$key}" value="{$val}" id="{$key}" class="input-large" placeholder="{$input}{$value['comment']}">
		<a href="javascript:void(0)">
			<span class="add-on datetime" target="{$key}"><i class="icon-calendar icon-blue"></i></span>
		</a>
		<span id="{$key}-label" class="label label-important hide"></span>
	</div>
</div>
HTML;
		break;
	default:
		echo <<<HTML
<div id="{$key}-control-group" class="control-group">
	<label for="{$key}" class="control-label">{$value['comment']}</label>
	<div class="controls">
		<input type="text" name="{$key}" value="{$model->$key}" id="{$key}" class="input-xlarge" placeholder="{$input}{$value['comment']}">
		<span id="{$key}-label" class="label label-important hide"></span>
	</div>
</div>
HTML;
		break;
}
?>
<?php endforeach;?>
<div class="control-group alert-danger">
	<label class="control-label"><?php echo __('Role');?></label>
	<div class="controls">
		<?php
			$user_role_list = $model->roles->find_all();
			$user_roles = array();
			foreach($user_role_list as $user_role){
				$user_roles[] = $user_role->id;
			}
		?>

		<?php foreach($roles as $role):?>
		<label for="role<?php echo $role->id?>" class="checkbox inline">
			<input id="role<?php echo $role->id?>" name="role[<?php echo $role->id?>]" type="checkbox" <?php echo in_array($role->id, $user_roles)?'checked="checked"':'';?> />
			<?php echo $role->name?>
        </label>
        <span class="add-on inline">
			<i class="icon-question-sign popover-help-top" title="<?php echo __('Help Text');?>" data-content="<?php echo $role->description?>"></i>
		</span>
		<?php endforeach;?>
	</div>
</div>