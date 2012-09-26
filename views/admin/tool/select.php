<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="panel">
	<div class="panel-header">
		<h2><i class="icon-cogs icon-blue"></i><?php echo __('Select result');?></h2>
	</div>
	<div class="panel-content">
		<table class="table table-bordered table-striped">
			<?php
				$max_show = 20;
				$count = count($result);
				if($count > 0):
			?>
			<thead>
				<tr>
					<?php foreach($result[0] as $key => $value):?>
					<th><?php echo __($key);?></th>
					<?php endforeach;?>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach($result as $item):
					if($max_show > 0):
				?>
				<tr>
					<?php foreach($item as $value):?>
					<td><?php echo $value;?></td>
					<?php endforeach;?>
				</tr>
				<?php
					endif;
					$max_show--;
					endforeach;
				?>
			</tbody>
			<?php else: ?>
		   		<tr><?php echo __('result is empty');?></tr>
			<?php endif;?>
			<?php echo __('Records').': '.$count?>
		</table>
	</div>
</div><!--/span-->