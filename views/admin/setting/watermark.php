<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="panel">
	<div class="panel-header">
		<h2><i class="icon-cogs icon-blue"></i><?php echo __('Watermark');?></h2>
	</div>
	<div class="panel-content">

		<form class="form-horizontal ajaxform" action="<?php echo Route::url('admin', array('controller' => 'setting', 'action' => 'watermark'));?>" method="post">
			<div class="control-group">
				<?php
					$status = Arr::get($watermark, 'status', 0);
					$status_arr = array(
						0 => array(
							'name' => __('Closed'),
							'checked' => '',
						),
						1 => array(
							'name' => __('Open'),
							'checked' => '',
						)
					);
					$status_arr[$status]['checked'] = 'checked="checked"';

				?>
				<label class="control-label"><?php echo __('Watermark').__('Status')?></label>
				<div class="controls">
				<?php foreach($status_arr as $key=>$value):?>
				<label class="radio inline">
					<input type="radio" <?php echo $value['checked'];?> value="<?php echo $key;?>" id="status" name="status">
					<?php echo $value['name'];?>
				</label>
				<?php endforeach;?>
				</div>
			</div>

			<div class="control-group">
				<?php
				$position = Arr::get($watermark, 'position', 'center');
				$water_position[$position]['checked'] = 'checked="checked"';
				?>
				<label class="control-label"><?php echo __('Watermark').__('Position')?></label>
				<div class="controls">
					<?php foreach($water_position as $key => $value):?>
					<label class="radio inline">
						<input type="radio" <?php echo $value['checked'];?> value="<?php echo $key;?>" id="status" name="position">
						<?php echo __($key);?>
					</label>
					<?php endforeach;?>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label"><?php echo __('Watermark').__('opacity')?></label>
				<div class="controls">
					<label class="inline"></label>
					<input type="text" value="<?php echo Arr::get($watermark, 'opacity', 50);?>" id="opacity" name="opacity">
					<span class="help-inline">设置水印图片与原始图片的融合度，范围为 1～100 的整数，数值越大水印图片透明度越低。</span>
				</div>
			</div>

			<div class="form-actions">
				<button class="btn btn-primary stateful" type="submit" data-loading-text="<?php echo __('submit')?>..."><?php echo __('Save');?></button>
				<button class="btn"><?php echo __('Cancel');?></button>

				<div class="btn-group pull-right">
					<a class="btn btn-success" target="_blank" href="<?php echo Route::url('admin', array('controller' => 'setting', 'action' => 'previewmark'))?>"><i class="icon-share icon-white"></i> <?php echo __('Watermark').__('Preview')?></a>
				</div>
			</div>
		</form>
	</div>
</div><!--/panel-->