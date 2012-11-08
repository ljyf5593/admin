<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="top-bar">
    <ul class="breadcrumb">
        <li><a href="<?php echo Route::url('admin')?>"><i class="icon-home"></i><?php echo __('Home')?></a></li>
        <?php
            $controller = Request::$current->controller();
            if($controller != 'home'):
        ?>
        <li><i class="icon-chevron-right icon-blue"></i></li>
        <li><a href="<?php echo Route::url('admin', array('controller' => $controller))?>"><?php echo __(ucfirst($controller));?></a></li>
        <?php endif;?>
        <?php
            $action = Request::$current->action();
            if($action != 'index'){
                echo '<li><i class="icon-chevron-right icon-blue"></i></li><li><span>'.__(ucfirst($action)).'</span></li>';
            }
        ?>
    </ul><!-- breadcrumbs -->

    <?php if(!empty($top_actions)):?>
    <div class="actions-bar">
        <div class="btn-group pull-right">
            <?php if(!empty($top_actions['menu'])):?>
            <?php foreach($top_actions['menu'] as $key => $value):?>
            <a class="btn btn-inverse" href="<?php echo Route::url('admin', array('controller' => $controller, 'action' => $key))?>"><i class="<?php echo $value['icon']?> icon-white"></i><?php echo __($value['name'])?></a>
            <?php endforeach?>
            <?php endif;?>
            <?php if(!empty($top_actions['dropdown'])):?>
            <a class="btn btn-inverse dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <?php foreach($top_actions['dropdown'] as $key => $value):?>
                <li><a href="<?php echo Route::url('admin', array('controller' => $controller, 'action' => $key))?>"><i class="<?php echo $value['icon']?>"></i> <?php echo __($value['name']);?></a></li>
                <?php endforeach?>
            </ul>
            <?php endif;?>
        </div> <!-- //btn-group -->
    </div> <!-- //actions-bar -->
    <?php endif;?>

</div> <!-- //top-bar -->
<div class="content">
    <?php if(isset($status) AND $status != '' AND $status_info != ''):?>
    <div class="span3 alert alert-<?php echo $status?> show-msg">
        <a class="close" data-dismiss="alert" href="#">×</a>
        <i class="icon-bullhorn icon-large"></i>
        <span><?php echo is_array($status_info)?current($status_info):$status_info;?></span>
    </div>
    <?php else:?>
    <div class="alert alert-success show-msg hide">
        <a class="close" data-dismiss="alert" href="#">×</a>
        <i class="icon-bullhorn icon-large"></i>
        <span></span>
    </div>
    <?php endif;?>
    <?php echo $main;?>

    <?php if (Kohana::$environment == Kohana::DEVELOPMENT) :?>
    <div id="kohana-profiler" style="padding: 100px 0 20px 0;"><?php echo View::factory('profiler/stats') ?></div>
    <?php endif;?>
</div> <!-- //content -->