<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 重载ORM，加载需要的方法，因此Admin模块需要在ORM之前加载
 *
 * @author  Jie.Liu (ljyf5593@gmail.com)
 * @Id $Id: orm.php 256 2013-04-04 09:55:49Z liujie $
 */
class ORM extends Kohana_ORM {
    
    const STATUS_AUDITE = 1; // 已审核
    const STATUS_UNAUDITE = 0; // 未审核

    const RECOMMEND = 1; // 推荐
    const UNRECOMMEND = 0; // 取消推荐

    const PUBLISH = 1;
    const UNPUBLISH = 0;

    // 搜索列
    protected $_search_row = array();
    // 显示列
    protected $_list_row = array();
    // 延伸字段，该字段不存在改表中但是仍要显示
    protected $_extend_row = array();
    // 单个操作
    protected $_operation = array();
    // 批量操作
    protected $_batch_operation = array();
    // 日期显示列
    public $date_row = array();
    // 时间显示列
    public $time_row = array('update', 'dateline');
    // 需要加载富文本编辑器的列
    public $editer_row = '';

    /**
     * 获取时间列的相关信息
     */
    public function getDateRow(){
        return array_intersect_key($this->_table_columns, array_fill_keys($this->_search_row, null));
    }

    /**
     * 获取搜索列的相关信息
     */
    public function getSearchRow(){
        return array_intersect_key($this->_table_columns, array_fill_keys($this->_search_row, null));
    }


    /**
     * 获取显示在列表页列
     */
    public function getListRow(){
        if(empty($this->_list_row)){
            $list_row = $this->_table_columns;
        } else {
            $list_row = array_intersect_key($this->_table_columns, array_fill_keys($this->_list_row, null));
        }

        return $list_row + $this->_extend_row;
    }

    /**
     * 获取批量操作信息
     * @return array
     */
    public function getBatchOperation(){
        return $this->_batch_operation;
    }

    /**
     * 获取单个操作
     */
    public function getOperation(){
        return $this->_operation;
    }

    /**
     * @static
     * 检查某一列是否可以模糊查询
     */
    public static function isFuzzyQuery(array $column){
        return $column['type'] === 'string';
    }

    /**
     * 将标识二进制位数的值生成十进制值
     * @param array $bin_bits
     * @return int|number
     */
    public function make_bin_value($bin_bits){
        if(is_array($bin_bits)){
            $attr = 0;
            for ($i = 0; $i < 8; $i++){
                if (!empty($bin_bits[$i+1])){
                    $attr += pow(2, $i);
                }
            }
        } else {
            $attr = $bin_bits;
        }
        return $attr;
    }

    /**
     * 解析成为二进制熟悉值
     * @param int $bin_value
     * @param string $name
     * @return array
     */
    public function parse_bin_value($name){
        $attr = intval($this->$name);
        $attrs = array();
        for($i=1; $i<=8; $i++) {
            $k = pow(2, $i-1);
            $attrs[$i] = ($attr & $k) ? 1 : 0;
        }
        return $attrs;
    }

    /**
     * 解析二进制数据对应的名称
     * @param $name
     */
    public function parse_bin_name($name){
        $value_arr = $this->parse_bin_value($name);
        $name_arr = $this->get_bin_names($name);
        $name= array();
        foreach($value_arr as $key => $value){
            if($value){
                $name[] = $name_arr[$key];
            }
        }
        return implode('/', $name);
    }

    /**
     * 获取二进制的选择表单
     */
    public function get_bin_checkbox($name){
        $bin_values = $this->parse_bin_value($name);
        $html = '';
        $name_arr = $this->get_bin_names($name);
        foreach($name_arr as $key=>$item){
            $html .= '<label class="checkbox inline" for="'.$name.$key.'">'.Form::checkbox("{$name}[{$key}]", 1, (bool)$bin_values[$key], array('id'=>"{$name}[{$key}]", 'class'=>'inline')).$item.'</label>';
        }
        return $html;
    }

    public function status_show(){
        return '<label class="radio inline" for="status_"'.self::STATUS_AUDITE.'>'.Form::radio('status', self::STATUS_AUDITE, $this->status==self::STATUS_AUDITE, array('id'=>"status_".self::STATUS_AUDITE, 'class'=>'inline')).'已审核</label><label class="radio inline" for="status"'.self::STATUS_UNAUDITE.'>'.Form::radio('status', self::STATUS_UNAUDITE, $this->status==self::STATUS_UNAUDITE, array('id'=>"status_".self::STATUS_UNAUDITE, 'class'=>'inline')).'未审核</label>';
    }

    public function recommend_show(){
        return '<label class="radio inline" for="recommend_"'.self::RECOMMEND.'>'.Form::radio('recommend', self::RECOMMEND, $this->recommend==self::RECOMMEND, array('id'=>"recommend_".self::RECOMMEND, 'class'=>'inline')).'推荐</label><label class="radio inline" for="recommend"'.self::UNRECOMMEND.'>'.Form::radio('recommend', self::UNRECOMMEND, $this->recommend==self::UNRECOMMEND, array('id'=>"recommend_".self::UNRECOMMEND, 'class'=>'inline')).'未推荐</label>';
    }

    public function publish_show(){
        return '<label class="radio inline" for="publish_"'.self::PUBLISH.'>'.Form::radio('publish', self::PUBLISH, $this->publish==self::PUBLISH, array('id'=>"publish_".self::PUBLISH, 'class'=>'inline')).'已发布</label><label class="radio inline" for="publish"'.self::UNPUBLISH.'>'.Form::radio('publish', self::UNPUBLISH, $this->publish==self::UNPUBLISH, array('id'=>"publish_".self::UNPUBLISH, 'class'=>'inline')).'未发布</label>';
    }

    /**
     * 审核通过
     */
    public function audit(){
        $this->status = self::STATUS_AUDITE;
        return $this->save();
    }

    /**
     * 审核不通过
     */
    public function unaudit(){
        $this->status = self::STATUS_UNAUDITE;
        return $this->save();
    }

    /**
     * 发布文章
     */
    public function publish() {
        $this->publish = self::PUBLISH;
        $this->save();
    }

    /**
     * 取消文章发布
     */
    public function unpublish() {
        $this->publish = self::UNPUBLISH;
        $this->save();
    }

    /**
     * 推荐到首页
     */
    public function recommend(){
        $this->recommend = self::RECOMMEND;
        return $this->save();
    }

    /**
     * 取消推荐
     */
    public function unrecommend(){
        $this->recommend = self::UNRECOMMEND;
        return $this->save();
    }

    /**
     * 后台列表页显示推荐状态
     */
    public function get_recommend(){
        return ($this->recommend == self::RECOMMEND)?'<a class="ajax" href="'.Route::url('admin/global', array('controller' => $this->_object_name, 'action' => 'single', 'operation' => 'unrecommend', 'id' => $this->pk())).'"><span class="label label-success"><i class="icon-heart icon-large"></i>'.__('recommend').'</span></a>':'<a class="ajax" href="'.Route::url('admin/global', array('controller' => $this->_object_name, 'action' => 'single', 'operation' => 'recommend', 'id' => $this->pk())).'"><span class="label label-warning"><i class="icon-heart-empty icon-large"></i>'.__('unrecommend').'</span></a>';
    }

    /**
     * 后台文章列表获取文章状态操作
     */
    public function get_publish(){
        return ($this->publish == self::PUBLISH) ?
            '<a class="ajax" href="'.Route::url('admin/global', array('controller' => $this->_object_name, 'action' => 'single', 'operation' => 'unpublish', 'id' => $this->pk())).'"><span class="label label-success"><i class="icon-eye-open icon-large"></i>'.__('Publish').'</span></a>' :
            '<a class="ajax" href="'.Route::url('admin/global', array('controller' => $this->_object_name, 'action' => 'single', 'operation' => 'publish', 'id' => $this->pk())).'"><span class="label label-warning"><i class="icon-eye-close icon-large"></i>'.__('Unpublish').'</span></a>';
    }

    /**
     * 后台列表页显示状态信息
     */
    public function get_status(){
        return ($this->status == self::STATUS_AUDITE)?'<a class="ajax" href="'.Route::url('admin/global', array('controller' => $this->_object_name, 'action' => 'single', 'operation' => 'unaudit', 'id' => $this->pk())).'"><span class="label label-success"><i class="icon-eye-open icon-large"></i>'.__('audited').'</span></a>':'<a class="ajax" href="'.Route::url('admin/global', array('controller' => $this->_object_name, 'action' => 'single', 'operation' => 'audit', 'id' => $this->pk())).'"><span class="label label-warning"><i class="icon-eye-close icon-large"></i>'.__('unaudited').'</span></a>';
    }

    /**
     * 获取类型相关名称
     * @return string
     */
    public function init_type_name(){
        $category = new Model_Coach_Category();
        $type_name = '';
        if ($this->parent) {
            $parent = $category->get_by_label($this->parent, Model_Coach_Category::LEVEL_PARENT, 0);
            if ($parent->loaded()) {
                $type_name = $parent->name;
                if ($this->child) {
                    $child = $category->get_by_label($this->child, Model_Coach_Category::LEVEL_CHILD, $parent->pk());
                    if ($child->loaded()) {
                        $type_name = $child->name;
                        if ($this->item) {
                            $item = $category->get_by_label($this->item, Model_Coach_Category::LEVEL_ITEM, $child->pk());
                            if ($item->loaded()) {
                                $type_name = $item->name;
                            }
                        }
                    }
                }
            }
        }

        return $type_name;
    }

    /**
     * 根据对象的地区数据生成地区名称
     * @param  Model_District $district [description]
     * @return [type]                   [description]
     */
    public function init_location(){
        $district = new Model_District();
        $location = array();
        if ($this->province) {
            $province = $district->get_by_label($this->province, Model_District::LEVEL_PROVINCE, 0);
            if ($province->loaded()) {

                if ($this->city) {
                    $city = $district->get_by_label($this->city, Model_District::LEVEL_CITY, $province->pk());
                    if ($city->loaded()) {
                        $location[] = $city->name;
                        if ($this->county) {
                            $county = $district->get_by_label($this->county, Model_District::LEVEL_COUNTY, $city->pk());
                            if ($county->loaded()) {
                                $location[] = $county->name;
                                if ($this->towns) {
                                    $towns = $district->get_by_label($this->towns, Model_District::LEVEL_TOWNS, $county->pk());
                                    if ($towns->loaded()) {
                                        $location[] = $towns->name;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return implode('', $location);
    }

    /**
     * 获取地理位置信息过滤插入的符号
     * @return mixed
     */
    public function get_location(){
        return str_replace('>', '', $this->location);
    }

    /**
     * 数据刷新
     */
    public function refresh(){
        $this->hits = DB::expr('hits + 1');
        $this->save();
    }

    /**
     * 获取二进制名称的数组
     */
    private function get_bin_names($name){
        $name_method = "get_{$name}_arr";
        return $this->{$name_method}();
    }

}