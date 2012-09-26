<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="panel">
	<div class="panel-header">
		<h2><i class="icon-cogs icon-blue"></i><?php echo __('File').__('Cache');?></h2>
	</div>
	<div class="panel-content">
		<?php echo HTML::anchor('/admin/tool/cache?ac=flush&driver=file', __('Flush').' '.__('File').' '.__('Cache'), array('class' => 'btn btn-toolbar ajax', 'title' => __('Flush').' '.__('File').' '.__('Cache')));?>
	</div>
</div>

<div class="panel">
	<div class="panel-header">
		<h2><i class="icon-cogs icon-blue"></i><?php echo 'Memcached';?></h2>
	</div>
	<div class="panel-content">
		<?php
		if($memcache instanceof Cache_Memcache){

			echo HTML::anchor('/admin/tool/cache?ac=flush&driver=memcache', __('Flush').' '.__('Memcache').' '.__('Cache'), array('class' => 'btn btn-toolbar ajax', 'title' => __('Flush').' '.__('Memcache').' '.__('Cache')));

		} else {

			echo '<div class="alert alert-info">Memecache is not found</div>';

		}
		?>
	</div>
</div>

<?php echo HTML::anchor('/admin/tool/cache?ac=flush&driver=all', __('Flush').' '.__('All').' '.__('Cache'), array('class' => 'btn btn-primary pull-right ajax', 'title' => __('Flush').' '.__('All').' '.__('Cache')));?>