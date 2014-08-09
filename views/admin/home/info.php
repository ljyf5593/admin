<?php defined('SYSPATH') or die('No direct script access.');?>
    <div class="panel">
        <div class="panel-header">
            <h2><i class="icon-pushpin icon-blue"></i><?php echo __('System Version')?></h2>
        </div>
        <div class="panel-content">
            <div class="row-fluid">
                <ul class="unstyled">
                <div class="span5">
                    <li>Version : <?php echo ADMIN_VERSION?> <i class="icon-ok"></i></li>
                    <li>Kohana : <?php echo Kohana::VERSION;?> <i class="icon-ok"></i></li>
                </div>
                <div class="span5">
                    <li>Server : <?php echo $_SERVER['SERVER_SOFTWARE'];?> <i class="icon-ok"></i></li>
                    <li>MySQL : <?php $mysql = DB::select(DB::expr("VERSION() as version"))->execute()->current(); echo $mysql['version'];?> <i class="icon-ok"></i></li>
                </div>
                </ul>
            </div>
        </div>
    </div><!--/span-->

    <div class="panel">
        <div class="panel-header">
            <h2><i class="icon-github icon-blue"></i><?php echo __('Third-party Plug')?></h2>
        </div>
        <div class="panel-content">
            <div class="row-fluid">
                <ul>
                    <li><a target="_blank" href="http://twitter.github.com/bootstrap/">Bootstrap, from Twitter</a> Version : 2.3.2</li>
                    <li><a target="_blank" href="http://fortawesome.github.com/Font-Awesome/">Font Awesome</a> Version : 3.2.1</li>
                    <li><a target="_blank" href="http://jquery.com/">jQuery</a> Version : 1.10.2</li>
                    <li><a target="_blank" href="https://github.com/malsup/form">jQuery Form Plugin</a> Version : 3.36</li>
                    <li><a target="_blank" href="http://bootboxjs.com/">Bootboxjs</a> Version : 3.3.0</li>
                    <li><a target="_blank" href="http://www.my97.net/"> My97 DatePicker</a> Version : 4.8 Beta4</li>
                    <li><a target="_blank" href="http://www.kindsoft.net/"> KindEditor</a> Version : 4.1.7</li>
                    <li><a target="_blank" href="https://github.com/ajaxray/Kohana-Log-Viewer"> Kohana Log Viewer</a></li>
                </ul>
            </div>
        </div>
    </div><!-- //span-->

    <div class="panel">
        <div class="panel-header">
            <h2><i class="icon-legal icon-blue"></i><?php echo __('Copyright')?></h2>
        </div>
        <div class="panel-content">
            Powered by <?php echo HTML::mailto('ljyf5593@gmail.com', '<i class="icon-envelope"></i>Jie.Liu');?>
            <br />
            Copyright © 2012-<?php echo date('Y');?>, Jie.Liu, All Rights Reserved
        </div>
    </div>