<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="panel">
    <div class="panel-header">
        <h2><i class="icon-cogs icon-blue"></i><?php echo __('ThirdPart');?></h2>
    </div>
    <div class="panel-content">

        <form class="form-horizontal ajaxform" action="<?php echo Route::url('admin', array('controller' => 'setting', 'action' => 'thirdpart'));?>" method="post">
            <fieldset>
                <div class="control-group">
                    <label for="statcode" class="control-label"><?php echo __('Statcode')?></label>
                    <div class="controls">
                        <textarea rows="3" id="statcode" name="statcode" class="input-block-level" ><?php echo Arr::get($config, 'statcode');?></textarea>
                        <span class="help-inline"></span>
                    </div>
                </div>

                <div class="control-group">
                    <label for="sina_weibo" class="control-label"><?php echo __('Sina weibo')?></label>
                    <div class="controls">
                        <textarea rows="3" id="sina_weibo" name="sina_weibo" class="input-block-level" ><?php echo Arr::get($config, 'sina_weibo');?></textarea>
                        <span class="help-block"><pre class="prettyprint">&lt;wb:follow-button uid="2054603883" type="gray_2" width="120" height="24" >&lt;/wb:follow-button></pre></span>
                    </div>
                </div>

                <div class="control-group">
                    <label for="qq_weibo" class="control-label"><?php echo __('QQ weibo')?></label>
                    <div class="controls">
                        <textarea rows="3" id="qq_weibo" name="qq_weibo" class="input-block-level" ><?php echo Arr::get($config, 'qq_weibo');?></textarea>
                        <span class="help-block"><pre class="prettyprint">&lt;iframe src="http://follow.v.t.qq.com/index.php?c=follow&a=quick&name=liujie_67&style=5&t=1354775331297&f=1" marginwidth="0" marginheight="0" allowtransparency="true" frameborder="0" height="24" scrolling="auto" width="110">&lt;/iframe></pre></span>
                    </div>
                </div>

                <div class="control-group">
                    <label for="share_plug" class="control-label"><?php echo __('share_plug')?></label>
                    <div class="controls">
                        <textarea rows="8" id="share_plug" name="jiathis" class="input-block-level" ><?php echo Arr::get($config, 'jiathis');?></textarea>
                        <span class="help-block">
                            请输入第三方分享插件代码
                        </span>
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