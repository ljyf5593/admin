<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="panel">
	<div class="panel-header">
		<h2><i class="icon-cogs icon-blue"></i><?php echo __('File').__('Cache');?></h2>
	</div>
	<div class="panel-content">
		<?php 
		$action_name = __('Flush').__('File').__('Cache');
		echo HTML::anchor('/admin/tool/cache?ac=flush&driver=file', $action_name, array('class' => 'btn ajax', 'title' => $action_name));?>
	</div>
</div>

<div class="panel">
	<div class="panel-header">
		<h2><i class="icon-cogs icon-blue"></i><?php echo 'Memcached';?></h2>
	</div>
	<div class="panel-content">
		<?php
		if($memcache instanceof Cache_Memcache){
			$action_name = __('Flush').__('Memcache').__('Cache');
			echo HTML::anchor('/admin/tool/cache?ac=flush&driver=memcache', $action_name, array('class' => 'btn ajax', 'title' => $action_name));

		} else {

			echo '<div class="alert alert-info">Memecache is not found</div>';

		}
		?>
	</div>
</div>

<?php 
$action_name = __('Flush').__('All').__('Cache');
echo HTML::anchor('/admin/tool/cache?ac=flush&driver=all', $action_name, array('class' => 'btn btn-primary pull-right ajax', 'title' => $action_name));?>