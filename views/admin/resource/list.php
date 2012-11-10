<?php
    defined('SYSPATH') or die('No direct script access.');
    $controller = Request::$current->controller();
?>
<div class="panel">
    <div class="panel-header">
        <h2><i class="icon-list-alt icon-blue"></i><?php echo __($model_name).__('List')?></h2>
        <div class="actions-bar">
            <div class="btn-group pull-right">
                <a class="btn btn-success btn-mini">Actions</a>
                <a class="btn btn-success btn-mini dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <?php echo HTML::anchor(Route::url('admin', array('controller' => $controller, 'action'=>'create')), '<i class="icon-plus"></i>'.__('Create').__($model_name), array('class' => 'ajax'));?>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="panel-content">
    <!-- //Search form -->
    <?php echo View::factory('admin/common/search')->bind('search', $search);?>

    <form action="<?php echo Route::url('admin/global', array('controller'=>$controller, 'action'=>'batch'))?>" class="ajaxform" method="post">
        <table class="table table-bordered table-striped">
            <!-- //表格头 -->
            <?php include Kohana::find_file('views', 'admin/common/thead')?>

            <tbody>
            <?php foreach($model_list as $model):?>
            <tr>
                <td><input class="selection" type="checkbox" name="ids[]" value="<?php echo $model->pk();?>"></td>
                <?php foreach($list_row as $key => $value):?>
                <td>
                <?php
                    switch($key){
                        case 'dateline':

                        case 'update':
                            echo date(Date::$timestamp_format, $model->$key);
                            break;

                        case 'attachment':
                            echo $model->$key.'&nbsp;<a target="_blank" href="'.URL::site('/upload/'.$model->$key).'"><img height="100" class="preview" src="'.URL::site('/upload/'.$model->$key).'"><i class="icon-picture icon-green icon-large"></i></a>';
                            break;

                        default :
                            echo  $model->$key;
                            break;
                    }
                ?>
                </td>
                <?php endforeach;?>

                <td>
                    <a class="btn btn-mini btn-info ajax" href="<?php echo Route::url('admin', array('controller'=>$controller, 'action' => 'edit', 'id'=>$model->pk()));?>"><i class="icon-edit"></i><?php echo __('Edit')?></a>
                    <a class="btn btn-mini btn-danger ajax" href="<?php echo Route::url('admin', array('controller'=>$controller, 'action'=>'delete','id'=>$model->pk()));?>" data-confirm="<?php echo __('Are you sure to delete this object')?>?"><i class="icon-trash"></i><?php echo __('Delete')?></a>
                </td>
            </tr>
            <?php endforeach;?>

            </tbody>
        </table>
        <!-- // 批量操作 -->
        <?php include Kohana::find_file('views', 'admin/common/batch')?>
    </form>
    <?php echo $pagination->render(); ?>
    </div>
</div>