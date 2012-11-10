<?php
    defined('SYSPATH') or die('No direct script access.');
    $controller = Request::$current->controller();
?>
<div class="panel">
    <div class="panel-header">
        <h2><i class="icon-list-alt icon-blue"></i><?php echo __($model_name).__('List')?></h2>
        <div class="actions-bar">
            <div class="btn-group pull-right">
                <?php echo HTML::anchor(Route::url('admin', array('controller' => $controller, 'action'=>'create'), TRUE), __('Create').__($model_name), array('class' => 'btn btn-success btn-mini ajax'));?>
                <a class="btn btn-success btn-mini dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <?php echo HTML::anchor(Route::url('admin', array('controller' => $controller, 'action'=>'create'), TRUE), '<i class="icon-plus"></i>'.__('Create').__($model_name), array('class' => 'ajax'));?>
                    </li>
                </ul>
            </div> <!-- // btn-group -->
        </div> <!--	// actions-bar -->
    </div> <!-- // panel-header -->

    <div class="panel-content">
    <!-- //Search form -->
    <?php
        if(!empty($search['search_row'])){
            echo View::factory('admin/common/search')->bind('search', $search);
        }
    ?>

        <form action="<?php echo Route::url('admin/global', array('controller'=>$model_name, 'action'=>'batch'))?>" class="ajaxform" method="post">
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

                            case 'update':
                                echo date(Date::$timestamp_format, $model->$key);
                                break;

                            default :
                                $func_name = 'get_'.$key;
                                if(method_exists($model, $func_name)){
                                    echo $model->$func_name();
                                } else {
                                    echo  $model->$key;
                                }
                                break;
                        }
                    ?>
                    </td>
                    <?php endforeach;?>

                    <td>
                        <a class="btn btn-mini btn-info ajax" href="<?php echo Route::url('admin', array('controller'=>$controller, 'action' => 'edit', 'id'=>$model->pk()));?>"><i class="icon-edit"></i><?php echo __('Edit')?></a>
                        <a class="btn btn-mini btn-danger ajax confirm" href="<?php echo Route::url('admin', array('controller'=>$controller, 'action'=>'delete','id'=>$model->pk()));?>" data-confirm="<?php echo __('Are you sure to delete this object')?>?"><i class="icon-trash"></i><?php echo __('Delete')?></a>
                    </td>
                </tr>
                <?php endforeach;?>

                </tbody>
            </table>
            
            <!-- // 批量操作 -->
            <?php include Kohana::find_file('views', 'admin/common/batch')?>

        </form>

        <?php echo $pagination->render(); ?>
    </div> <!-- //panel-content -->

</div> <!-- //pannel -->