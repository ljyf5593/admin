<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="well">
	<?php if(!empty($batch_operations)):?>
	<?php foreach($batch_operations as $batch_action => $batch):?>
	<a class="btn btn-small btn-<?php echo Arr::get($batch, 'style', 'warning');?> batch" rel="<?php echo $batch_action?>"><i class="icon-<?php echo Arr::get($batch, 'icon', 'exclamation-sign');?>"></i> <?php echo __(Arr::get($batch, 'name', $batch_action));?></a>
	<?php endforeach;?>
	<?php endif;?>
	<a class="btn btn-small btn-danger batch" rel="delete"><i class="icon-remove"></i><?php echo __('Remove').' '.__('selected')?></a>
</div> <!-- //well -->