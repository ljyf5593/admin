<?php defined('SYSPATH') or die('No direct script access.');?>
<td>
    <a class="btn btn-mini btn-info ajax-modal" href="<?php echo Route::url('admin', array('controller'=>$controller, 'action' => 'edit', 'id'=>$model->pk()));?>"><i class="icon-edit"></i><?php echo __('Edit')?></a>
    <a class="btn btn-mini btn-danger ajax" href="<?php echo Route::url('admin', array('controller'=>$controller, 'action'=>'delete','id'=>$model->pk()));?>" data-confirm="<?php echo __('Are you sure to delete this object')?>?"><i class="icon-trash"></i><?php echo __('Delete')?></a>
    <?php
    $operation = $model->getOperation();
    if(!empty($operation)){
        foreach($operation as $action => $value){
            echo '<a class="btn btn-mini btn-info ajax" href="'.Route::url('admin', array('controller'=>$controller,
                'action' => $action, 'id'=>$model->pk())).'"><i class="'.$value['icon'].'"></i>'. __($value['name'])
                .'</a>';
        }
    }
    ?>
</td>