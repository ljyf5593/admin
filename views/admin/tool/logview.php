<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="panel">
    <div class="panel-header">
        <h2><i class="icon-cogs icon-blue"></i><?php echo __('Logview');?></h2>
    </div>
    <div class="panel-content">
        <?php $request = Request::$current;?>
        <form method="get" action="<?php echo Route::url('admin', array('controller' => $request->controller(), 'action' => 'logview'));?>" class="well form-inline ajaxform">
            Level
            <select class="input-small" name="level">
                <option value="">--All--</option>
                <?php
                foreach (Model_Logreport::$levels as $key => $level):
                    $select = ($current_level == $key) ? 'selected="selected"' : '';
                    echo "<option $select value=\"". strtoupper($key).'">'.$key.'</option>';
                endforeach;
                ?>
            </select>

            <label class="input-append">
                Date
                <input type="text" class="input-large date" name="date" id="date" value="<?php echo $date;?>">
                <span class="add-on date" target="date">
                    <i class="icon-calendar icon-blue"></i>
                </span>
            </label>
            <button type="submit" class="btn btn-primary"><i class="icon-search"></i><?php echo __('View');?></button>

            <div class="btn-group pull-right">
            <?php
                $params = array();
                $params['level'] = $request->query('level');
                $params['date'] = $request->query('date');
            ?>
            <?php if($mode == 'raw'): ?>
            <a class="btn btn-success ajax" href="<?php echo Route::url('admin', array('controller' => $request->controller(), 'action' => 'logview'));?>?mode=formatted&<?php echo http_build_query($params)?>"><?php echo __('formatted mode');?></a>
            <?php else: ?>
            <a class="btn btn-info ajax" href="<?php echo Route::url('admin', array('controller' => $request->controller(), 'action' => 'logview'));?>?mode=raw&<?php echo http_build_query($params)?>"><?php echo __('raw mode');?></a>
            <?php endif; ?>
            <a class="btn btn-danger ajax" href="<?php echo Route::url('admin', array('controller' => $request->controller(), 'action' => 'deletelog', 'id' => $date)) ?>" data-confirm="<?php echo __('Are you sure to execute the operation');?>?"><?php echo __('clear this logs');?></a>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <?php if($mode != 'raw'): ?>
            <thead>
            <tr>
                <th width="5%">Level</th>
                <th width="10%">Time</th>
                <th width="30%">Type</th>
                <th width="65%">File</th>
            </tr>

            </thead>
            <?php endif; ?>
            <tbody>
            <?php foreach ($logs as $log):?>
                    <tr>
                    <?php if($mode != 'raw'): ?>
                        <td rowspan="2">
                            <label class="label label-<?php echo Arr::get($log,'style') ?>"> <?php echo Arr::get($log,'level') ?> </label>
                        </td>
                        <td><?php echo date('H:i:s', Arr::get($log,'time')) ?></td>
                        <td><?php echo Arr::get($log,'type') ?></td>
                        <td><?php echo Arr::get($log,'file') ?></td>

                    </tr>
                    <tr><td colspan="4"><b>Message: </b><?php echo Arr::get($log,'message') ?></td></tr>
                    <?php else: // Raw mode ?>
                    <tr>
                        <td>
                            <span class="label label-<?php echo Arr::get($log,'style') ?>"> <?php echo Arr::get($log,'level') ?></span> &nbsp;
                            <?php echo Arr::get($log,'raw') ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</div>