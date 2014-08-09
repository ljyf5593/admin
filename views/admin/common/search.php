<?php defined('SYSPATH') or die('No direct script access.');?>
<?php
    $search_row = $search['search_row'];
    if( ! empty($search_row)):
?>
<!--//列表搜索表单 -->
<form class="well form-inline ajaxform" action="<?php echo Route::url('admin/list', array('controller'=>Request::$current->controller(), 'action'=>'list'));?>" method="get">
    <?php
        $model = $search['model'];
        // 获取时间和日期类字段
        $date_row = $model->date_row;
        $time_row = $model->time_row;
        if( ! empty($search_row)){
            foreach($search_row as $key => $value){
                $func = $key.'_search';
                $cut_len = UTF8::strpos($value['comment'], '(');
                $comment = $cut_len ? UTF8::substr($value['comment'], 0, $cut_len):$value['comment'];
                if(method_exists($model, $func)){
                    echo '<label class="search-label">'.$comment.'</label>'.$model->$func();

                } else { // 判断是否可以模糊查询，是则显示一个特殊标识
                    $extend_icon = '';
                    $input_class = 'input-mini';
                    if (ORM::isFuzzyQuery($value)) {
                        $extend_icon .= ' <i class="icon-asterisk popover-help-top" title="'.__('Help Text').'" data-content="'.__('Fuzzy query').'"></i>';
                        $input_class = 'input-medium';
                    }

                    if (in_array($key, $date_row)) {
                        $input_class = 'input-medium date';
                    }

                    if (in_array($key, $time_row)) {
                        $input_class = 'input-medium datetime';
                    }                    

                    echo '<label class="search-label">'.$comment.$extend_icon.'</label>'.Form::input($key, NULL, array('class' => $input_class, 'placeholder' => $value['comment']));
                }
            }
        }
    ?>
    <button type="submit" class="btn btn-primary">
        <i class="icon-search"></i>
        <?php echo __('Search');?>
    </button>
</form>
<?php endif;?>