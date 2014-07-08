<?php defined('SYSPATH') or die('No direct script access.');?>
<?php $num = 0; foreach($list_columns as $key=>$value): $num++;?>
<?php
echo $num%2 ? '<div class="row-fluid">' : '';
// 获取各列的消息提示信息
list($message_label, $label_class) = Admin::get_message_label($key, $message);

switch($key){
    case $model->editer_row: // 需要加载富文本的列，不在这里显示
        $num--;
        break;
    case 'id':
        echo <<<HTML
<div id="{$key}-control-group" class="control-group span6 {$label_class}">
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
<div id="{$key}-control-group" class="control-group span6 {$class} {$label_class}">
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
        {$message_label}
    </div>
</div>
HTML;
        break;
    case 'password':
        $num--;
        $icon = '<i class="icon-info-sign icon-red popover-help-top" title="'.__('Alert Text').'" data-content="'.__('Change your password, please fill in here, otherwise leave blank').'"></i>';
        echo <<<HTML
<div id="{$key}-control-group" class="control-group span6 alert-danger">
    <label for="{$key}" class="control-label">
        {$value['comment']}
    </label>
    <div class="controls input-append">
        <input type="password" name="{$key}" value="" id="{$key}" class="input-xlarge" placeholder="{$value['comment']}">
        <span class="add-on">
            {$icon}
        </span>
        <span id="{$key}-label" class="label label-important hide"></span>
    </div>
</div>
<div id="password_confirm-control-group" class="control-group span6 alert-danger">
    <label for="password_confirm" class="control-label">确认{$value['comment']}</label>
    <div class="controls">
        <input type="password" name="password_confirm" value="" id="password_confirm" class="input-xlarge" placeholder="确认{$value['comment']}">
        <span id="password_confirm-label" class="label label-important hide"></span>
    </div>
</div>
HTML;
        break;
    default:
    ?>
<div id="<?php echo $key;?>-control-group" class="control-group span6 <?php echo $label_class?>">
    <label for="<?php echo $key;?>" class="control-label"><?php echo $value['comment'];?></label>
    <?php if(in_array($key, $model->time_row)):
        $val = "";
        if($model->$key){
            $val = date(Date::$timestamp_format, $model->$key);
        }
    ?>
        <div class="controls input-append">
            <input type="text" name="<?php echo $key;?>" value="<?php echo $val;?>" id="<?php echo $key;?>" class="input-large" placeholder="<?php echo $value['comment'];?>">
            <a href="javascript:void(0)">
                <span class="add-on datetime" target="<?php echo $key;?>"><i class="icon-calendar icon-blue"></i></span>
            </a>
        <?php echo $message_label;?>
    <?php elseif(in_array($key, $model->date_row)):
        $val = "";
        if($model->$key){
            $val = date('Y-m-d', $model->$key);
        }
    ?>
        <div class="controls input-append">
            <input type="text" name="<?php echo $key;?>" value="<?php echo $val;?>" id="<?php echo $key;?>" class="input-large" placeholder="<?php echo $value['comment'];?>">
            <a href="javascript:void(0)">
                <span class="add-on date" target="<?php echo $key;?>"><i class="icon-calendar icon-blue"></i></span>
            </a>
        <?php echo $message_label;?>
    <?php else:?>
        <div class="controls">
        <?php
            $func = $key.'_show';
            if(method_exists($model, $func)){

                echo $model->$func();

            } elseif ( ($value['data_type'] == 'varchar' AND $value['character_maximum_length'] > 255) OR $value['data_type'] == 'text') {

                echo Form::textarea($key, $model->$key, array('id' => $key, 'class' => 'input-xlarge', 'placeholder' => __('Input').$value['comment']));

            } else {

                echo Form::input($key, $model->$key, array('id' => $key, 'class' => 'input-xlarge', 'placeholder' => __('Input').$value['comment']));

            }
            echo $message_label;
        ?>
    <?php endif;?>
    </div>
</div>
    <?php
        break;
}
echo $num%2?'':'</div>';
endforeach;
echo $num%2?'</div>':'';
    ?>

<div class="control-group alert-danger">
    <label class="control-label"><?php echo __('Role');?></label>
    <div class="controls">
        <?php
            $user_role_list = $model->roles->find_all();
            $user_roles = array();
            foreach($user_role_list as $user_role){
                $user_roles[] = $user_role->id;
            }
        ?>

        <?php foreach($roles as $role):?>
        <label for="role<?php echo $role->id?>" class="checkbox inline">
            <input id="role<?php echo $role->id?>" name="role[<?php echo $role->id?>]" type="checkbox" <?php echo in_array($role->id, $user_roles)?'checked="checked"':'';?> />
            <?php echo $role->name?>
        </label>
        <span class="add-on inline">
            <i class="icon-question-sign popover-help-top" title="<?php echo __('Help Text');?>" data-content="<?php echo $role->description?>"></i>
        </span>
        <?php endforeach;?>
    </div>
</div>