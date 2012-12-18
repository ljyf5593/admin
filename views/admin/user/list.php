<?php
    defined('SYSPATH') or die('No direct script access.');
    $controller = Request::$current->controller();
?>
<div class="panel">
    <div class="panel-header">
        <h2><i class="icon-list-alt icon-blue"></i><?php echo __($model_name).__('List')?></h2>
        <div class="actions-bar">
            <div class="btn-group pull-right">
                <a class="btn btn-success btn-mini ajax" href="<?php echo Route::url('admin', array('controller' => $controller, 'action'=>'create'))?>"><i class="icon-plus"></i><?php echo __('Create').__('User');?></a>
                <a class="btn btn-success btn-mini dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="ajax" href="<?php echo Route::url('admin', array('controller' => $controller, 'action'=>'create'))?>"><i class="icon-plus"></i><?php echo __('Create').__('User');?></a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="panel-content">
    <?php echo View::factory('admin/common/search')->bind('search', $search);?>

    <form action="<?php echo Route::url('admin/global', array('controller'=>$controller, 'action'=>'batch'))?>" class="ajaxform" method="post">
        <table class="table table-bordered table-striped">
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

                        case 'last_login':
                            echo date(Date::$timestamp_format, $model->$key);
                            break;

                        case 'active':
                            echo ($model->$key === '1')?'<a class="ajax" href="'.Route::url('admin/global', array('controller' => $controller, 'action' => 'single', 'operation' => 'lock', 'id' => $model->pk())).'"><span class="label label-success"><i class="icon-unlock"></i>'.__('Unlock').'</span></a>':'<a class="ajax" href="'.Route::url('admin/global', array('controller' => $controller, 'action' => 'single', 'operation' => 'unlock', 'id' => $model->pk())).'"><span class="label label-warning"><i class="icon-lock"></i>'.__('Lock').'</span></a>';
                            break;
                        default :
                            echo $model->$key;
                    }
                ?>
                </td>
                <?php endforeach;?>
                <!-- // 单个操作 -->
                <?php include Kohana::find_file('views', 'admin/common/operation')?>
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