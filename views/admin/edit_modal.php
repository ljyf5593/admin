<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4><i class="icon-edit icon-blue"></i><?php echo __(ucfirst($action)).__($model_name);?></h4>
</div>

<form class="form-horizontal ajaxform" method="post" action="<?php echo Route::url('admin', array('controller' => Request::$current->controller(), 'action' => 'update', 'id' => $model->pk()))?>">
    <div class="modal-body">
        <fieldset>
            <?php echo $main;?>
        </fieldset>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" type="submit"><?php echo __('Save');?></button>
        <button class="btn" type="reset"><?php echo __('Reset');?></button>
    </div>
</form>