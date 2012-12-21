<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="panel">
	<div class="panel-header">
		<h2><i class="icon-list-ul icon-blue"></i><?php echo __('Category');?></h2>
	</div>
	<div class="panel-content">
		<form class="ajaxform" method="post" action="<?php echo Route::url('admin', array('controller' => 'article', 'action' => 'category'));?>">
			<table class="table">
				<thead>
				<tr>
					<th>栏目ID</th>
					<th>栏目名称</th>
					<th>文章数</th>
					<th>操作</th>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($category)):?>
					<?php echo $category;?>
				<?php else:?>
	<tr>
		<td>&nbsp;</td>
		<td>
			<div><a href="javascript:void(0);" class="addtr" level="-1" catid="0">添加顶级栏目</a></div>
		</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
				<?php endif;?>
				</tbody>
			</table>
			<div class="well">
				<button class="btn btn-primary pull-right"><?php echo __('Save');?></button>
			</div>
		</form>
	</div>

</div>