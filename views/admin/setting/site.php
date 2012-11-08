<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="panel">
    <div class="panel-header">
        <h2><i class="icon-cogs icon-blue"></i><?php echo __('Site').__('Setting');?></h2>
    </div>
    <div class="panel-content">

        <form class="form-horizontal ajaxform" action="<?php echo Route::url('admin', array('controller' => 'setting', 'action' => 'site'));?>" method="post">
            <fieldset>
                <div class="control-group">
                    <label for="sitename" class="control-label"><?php echo __('Site Name')?></label>
                    <div class="input-append">
                        <input type="text" value="<?php echo Arr::get($config, 'sitename');?>" id="sitename" name="sitename" class="input-xlarge">
                        <span class="add-on">
                            <i class="icon-question-sign icon-blue popover-help" title="<?php echo __('Help Text')?>" data-content="<?php echo __('sitename will be show in the web title')?>"></i>
                        </span>
                    </div>
                </div>
                <div class="control-group">
                    <label for="sitetitle" class="control-label"><?php echo __('Site Title')?></label>
                    <div class="input-append">
                        <input type="text" id="sitetitle" name="sitetitle" class="input-xlarge" value="<?php echo Arr::get($config, 'sitetitle');?>">
                        <span class="add-on">
                            <i class="icon-question-sign icon-blue popover-help" title="<?php echo __('Help Text')?>" data-content="<?php echo __('sitetitle will be show in home page at the title')?>"></i>
                        </span>
                    </div>
                </div>
                <div class="control-group">
                    <label for="meta_keywords" class="control-label"><?php echo __('Meta Keywords')?></label>
                    <div class="input-append">
                        <input type="text" id="meta_keywords" name="meta_keywords" class="input-xlarge" value="<?php echo Arr::get($config, 'meta_keywords');?>">
                        <span class="add-on">
                            <i class="icon-question-sign icon-blue popover-help" title="<?php echo __('Help Text')?>" data-content="<?php echo __('Keywords for Search engine')?>"></i>
                        </span>
                    </div>
                </div>
                <div class="control-group">
                    <label for="meta_description" class="control-label"><?php echo __('Meta Description')?></label>
                    <div class="controls">
                        <textarea rows="3" id="meta_description" name="meta_description" class="input-xlarge"><?php echo Arr::get($config, 'meta_description');?></textarea>
                        <span class="help-inline"><?php echo __('meta description for Search engine');?></span>
                    </div>
                </div>
                <div class="control-group alert-danger">
                    <label  class="control-label"><?php echo __('Closed Site');?>
                        <span class="add-on">
                            <i class="icon-info-sign icon-red popover-help-top" title="<?php echo __('Alert Text')?>" data-content="<?php echo __('Closed Site Text');?>"></i>
                        </span>
                    </label>
                    <div class="controls">
                        <label class="radio inline">
                            <?php echo Form::radio('closed_site', 'true', Arr::get($config, 'closed_site') == 'true', array('id'=>'closed_site')); echo __('Closed');?>
                        </label>
                        <label class="radio inline">
                            <?php echo Form::radio('closed_site', 'false', Arr::get($config, 'closed_site') == 'false'); echo __('Open');?>
                        </label>
                    </div>
                </div>
                <div class="control-group">
                    <label for="closed_info" class="control-label"><?php echo __('Closed Info')?></label>
                    <div class="controls">
                        <textarea rows="3" id="closed_info" name="closed_info" class="input-xlarge"><?php echo Arr::get($config, 'closed_info');?></textarea>
                        <span class="help-inline"><?php echo __('closed_info_text')?></span>
                    </div>
                </div>
                <div class="control-group">
                    <label for="statcode" class="control-label"><?php echo __('Statcode')?></label>
                    <div class="controls">
                        <textarea rows="3" id="statcode" name="statcode" class="input-xlarge" ><?php echo Arr::get($config, 'statcode');?></textarea>
                        <span class="help-inline"></span>
                    </div>
                </div>

                <div class="form-actions">
                    <button class="btn btn-primary stateful" type="submit" data-loading-text="<?php echo __('submit')?>..."><?php echo __('Save');?></button>
                    <button class="btn" type="reset"><?php echo __('Cancel');?></button>

                </div>
            </fieldset>
        </form>
    </div>
</div><!--/panel-->