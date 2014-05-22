<?php
defined('SYSPATH') or die('No direct script access.');
$controller = Request::$current->controller();
?>
<div class="panel">
    <div class="panel-header">
        <h2><i class="icon-list-alt icon-blue"></i><?php echo __($model_name).__('List')?></h2>
        <div class="actions-bar">
            <div class="btn-group pull-right">
                <a class="btn btn-success btn-mini ajax-modal" href="<?php echo Route::url('admin', array('controller' => $controller, 'action'=>'create'))?>"><i class="icon-plus"></i><?php echo __('Create').__('User');?></a>
                <a class="btn btn-success btn-mini dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="ajax-modal" href="<?php echo Route::url('admin', array('controller' => $controller, 'action'=>'create'))?>"><i class="icon-plus"></i><?php echo __('Create').__('User');?></a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="panel-content">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <?php foreach($list_row as $key => $value):?>
                <th><a class="ajax" href="<?php echo Route::url('admin/list', array(
                        'controller' => $controller,
                        'action' => 'list',
                        'page' => $pagination->current_page,
                        'order' => $key,
                        'by' => ($by === 'desc') ? 'asc' : 'desc',
                    ))?>">
                        <?php
                        echo $value['comment'];
                        $by_icon = array(
                            'desc' => 'icon-sort-down',
                            'asc' => 'icon-sort-up'
                        );
                        if($order === $key){
                            echo '<i class="'.$by_icon[$by].'"></i>';
                        } else {
                            echo '<i class="icon-sort"></i>';
                        }
                        ?></a>
                </th>
                <?php endforeach;?>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($model_list as $model):?>
                <tr>
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
                    <td><a class="btn btn-mini btn-info ajax-modal" href="<?php echo Route::url('admin', array('controller'=>'User', 'action' => 'edit', 'id'=>$model->pk()));?>"><i class="icon-edit"></i><?php echo __('Edit')?></a></td>
                </tr>
            <?php endforeach;?>

            </tbody>
        </table>
        <?php echo $pagination->render(); ?>
    </div>
</div>