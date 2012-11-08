<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="panel">
    <div class="panel-header">
        <h2><i class="icon-tasks icon-blue"></i><?php echo __(ucfirst($action)).__($model_name);?></h2>
    </div>
    <div class="panel-content row-fluid">
        <form class="form-horizontal ajaxform" method="post" action="<?php echo Route::url('admin', array('controller' => Request::$current->controller(), 'action' => 'update', 'id' => $model->pk()))?>">
            <fieldset>
                <?php $num = 0; foreach($list_columns as $key=>$value): $num++;?>
                <?php
                echo $num%2 ? '<div class="row-fluid">' : '';

                switch($key){
                    case 'id':
                        echo <<<HTML
                <div id="{$key}-control-group" class="control-group span6">
                    <label for="{$key}" class="control-label">{$value['comment']}</label>
                    <div class="controls">
                        <span class="input-xlarge uneditable-input">{$model->id}</span>
                    </div>
                </div>
HTML;
                        break;
                    case 'active':
                        $status = array(
                            1 => __('Enable'),
                            0 => __('Disable'),
                        );
                        $val = $model->$key;
                        $is_recommended = ($val == 1);
                        if($is_recommended){
                            $class = 'alert-info';
                            $icon = '<i class="icon-info-sign icon-red popover-help-top" title="'.__('Alert Text').'" data-content="'.__($model_name.' '.$key.' is Enable').'"></i>';
                        } else {
                            $class = 'alert';
                            $icon = '<i class="icon-info-sign icon-red popover-help-top" title="'.__('Alert Text').'" data-content="'.__($model_name.' '.$key.' is Disable').'"></i>';
                        }
                        echo <<<HTML
                <div id="{$key}-control-group" class="control-group span6 {$class}">
                    <label for="{$key}" class="control-label">
                        <span class="add-on">
                            {$value['comment']}{$icon}
                        </span>
                    </label>
                    <div class="controls">
                        <label class="radio inline">
                            <input type="radio" name="{$key}" value="1" checked="">{$status[1]}
                        </label>
                        <label class="radio inline">
                            <input type="radio" name="{$key}" value="0">{$status[0]}
                        </label>
                        <span id="{$key}-label" class="label label-important hide"></span>
                    </div>
                </div>
HTML;
                        break;
                    case 'update':
                        break;
                    case 'dateline':
                        $val = "";
                        if($model->$key){
                            $val = date(Date::$timestamp_format, $model->$key);
                        }

                        echo <<<HTML
                <div id="{$key}-control-group" class="control-group span6">
                    <label for="{$key}" class="control-label">{$value['comment']}</label>
                    <div class="controls input-append">
                        <input type="text" name="{$key}" value="{$val}" id="{$key}" class="input-large" placeholder="{$value['comment']}">
                        <a href="javascript:void(0)">
                            <span class="add-on datetime" target="{$key}"><i class="icon-calendar icon-blue"></i></span>
                        </a>
                        <span id="{$key}-label" class="label label-important hide"></span>
                    </div>
                </div>
HTML;
                        break;
                    default:
                    ?>
                <div id="<?php echo $key;?>-control-group" class="control-group span6">
                    <label for="<?php echo $key;?>" class="control-label"><?php echo $value['comment'];?></label>
                    <div class="controls">
                    <?php
                        $func = $key.'_show';
                        if(method_exists($model, $func)){

                            echo $model->$func();

                        } elseif ($value['data_type'] == 'varchar' AND $value['character_maximum_length'] > 255) {

                            echo Form::textarea($key, $model->$key, array('id' => $key, 'class' => 'input-xlarge', 'placeholder' => __('Input').$value['comment']));

                        } else {

                            echo Form::input($key, $model->$key, array('id' => $key, 'class' => 'input-xlarge', 'placeholder' => __('Input').$value['comment']));

                        }
                    ?>
                        <span id="<?php echo $key;?>-label" class="label label-important hide"></span>
                    </div>
                </div>
                    <?php
                        break;
                }
                echo $num%2?'':'</div>';
                endforeach;
                echo $num%2?'</div>':'';
                    ?>

                <div class="form-actions">
                    <button class="btn btn-primary" type="submit"><?php echo __('Save');?></button>
                    <button class="btn" type="reset"><?php echo __('Reset');?></button>
                </div>
            </fieldset>
        </form>
    </div>
</div><!--/span-->