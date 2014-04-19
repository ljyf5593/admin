<?php defined('SYSPATH') or die('No direct script access.');?>
<?php foreach($list_columns as $key=>$value):?>
<?php
switch($key){
	case 'id':
		echo <<<HTML
<div id="{$key}-control-group" class="control-group">
	<label for="{$key}" class="control-label">ID</label>
	<div class="controls">
		<span class="input-xlarge uneditable-input">{$model->id}</span>
	</div>
</div>
HTML;
		break;

	case 'name':
	?>
	<div id="<?php echo $key;?>-control-group" class="control-group">
		<label for="<?php echo $key;?>" class="control-label"><?php echo $value['comment'];?></label>
		<div class="controls">
			<?php
			if(in_array($model->name, $sys_roles)){
				echo Form::input($key, $model->name, array('id' => $key, 'class' => 'input-xlarge', 'disabled' => 'disabled', 'placeholder' => __('Input').$value['comment']));
			} else {
				echo Form::input($key, $model->name, array('id' => $key, 'class' => 'input-xlarge', 'placeholder' => __('Input').$value['comment']));
			}
			?>
			<span id="{$key}-label" class="label label-important hide"></span>
		</div>
	</div>
	<?php
		break;

	case 'permissions':

		break;

	default:
	?>
<div id="<?php echo $key;?>-control-group" class="control-group">
	<label for="<?php echo $key;?>" class="control-label"><?php echo $value['comment'];?></label>
	<div class="controls">
	<?php
		if($value['data_type'] == 'varchar' AND $value['character_maximum_length'] >= 255){
			echo Form::textarea($key, $model->$key, array('id' => $key, 'class' => 'input-xlarge', 'placeholder' => __('Input').$value['comment']));
		} else {
			echo Form::input($key, $model->$key, array('id' => $key, 'class' => 'input-xlarge', 'placeholder' => __('Input').$value['comment']));
		}
	?>
		<span id="{$key}-label" class="label label-important hide"></span>
	</div>
</div>
	<?php
		break;
}
?>

<?php endforeach;?>