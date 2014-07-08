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

<?php
    if($model->editer_row != ''):
        $key = $model->editer_row;
        list($message_label, $label_class) = Admin::get_message_label($key, $message);
?>
<div id="content-control-group" class="control-group <?php echo $label_class?>">
    <label for="content-edit" class="control-label"><?php echo $list_columns[$key]['comment'];?></label>
    <div class="controls">
        <textarea type="text" name="<?php echo $key;?>" id="content-edit" class="span10" rows="15"><?php echo $model->$key;?></textarea>
        <?php echo $message_label;?>
    </div>
</div>
<?php endif;?>