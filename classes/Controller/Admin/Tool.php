<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 工具控制器
 *
 * @author  Jie.Liu (ljyf5593@gmail.com)
 * @Id $Id: tool.php 68 2012-08-28 10:16:13Z Jie.Liu $
 */
class Controller_Admin_Tool extends Controller_Admin {

    /**
     * 面包屑右边的菜单
     * @var array
     */
    protected $top_actions = array(
        'menu' => array(
            'backup' => array(
                'name' => 'Backup',
                'icon' => 'icon-share',
            ),
            'upgrade' => array(
                'name' => 'Upgrade',
                'icon' => 'icon-refresh',
            ),
        ),
        'dropdown' => array(
            'logview' => array(
                'name' => 'Logview',
                'icon' => 'icon-dashboard',
            ),
            'cache' => array(
                'name' => 'Cache',
                'icon' => 'icon-bar-chart',
            ),
        ),
    );

    public function action_index(){
        $this->action_backup();
    }

    /**
     * 数据库备份
     */
    public function action_backup(){
        $db = Database::instance();
        $table_prefix = $db->table_prefix();
        if(HTTP_Request::POST === $this->request->method()){
            $tables = $this->request->post('tables');

            if(!empty($tables)){

                $sql = '-- Comasa '.ADMIN_VERSION."\n\n";
                foreach($tables as $table){
                    $full_table = $db->quote_table($table);
                    $sql .= "DROP TABLE IF EXISTS {$full_table};\n";
                    $result = DB::query(Database::SELECT, "SHOW CREATE TABLE {$full_table}")->execute()->current();
                    $sql .= $result['Create Table'];
                    $sql .= ";\n";
                    $result = DB::select()->from($table)->execute();
                    foreach($result as $row){
                        $sql .= "INSERT INTO {$full_table} (".implode(',', array_map(array($db, 'quote_identifier'), array_keys($row))).")  VALUES ".$db->quote($row).";\n";
                    }
                }

                $sql .= "\n-- ".date(Date::$timestamp_format);
                $file_name = 'backup_'.time().'.sql';

                $type = $this->request->post('type');
                if($type == 'online') {
                    file_put_contents(DOCROOT.'/'.$file_name, $sql);
                    $this->set_status('success', __('Backup the Database to the file :file_name success', array(':file_name' => $file_name)));

                } elseif($type == 'local'){

                    echo $this->response->headers(array(
                        'Content-Encoding' => 'none',
                        'Content-Disposition' => 'attachment; filename="'.$file_name.'"',
                        'Pragma' => 'no-cache',
                        'Expires' => '0',
                    ))->headers('Content-Type', 'application/octet-stream')->send_headers()->body($sql);
                    die();

                }

            } else {
                $this->set_status('error', 'Table must to be selected');
            }
        }

        $this->main = View::factory('admin/tool/backup');

        //数据表显示项
        $options = array('Name', 'Engine', 'Rows', 'Data_length', 'Index_length', 'Auto_increment', 'Create_time', 'Collation', 'Comment');
        $tables = DB::query(Database::SELECT, "SHOW TABLE STATUS")->execute();
        $this->main->tables = $tables;
        $this->main->options = $options;
        $this->main->table_prefix = $table_prefix;
    }

    /**
     * 数据库升级
     */
    public function action_upgrade(){
        if(HTTP_Request::POST === $this->request->method()){

            $sql = $this->request->post('sql');
            if($sql){
                $type = $this->request->post('type');
                try{
                    switch($type){
                        case 'select':
                            $result = Database::instance()->query(Database::SELECT, $sql);
                            $this->main = View::factory('admin/tool/select');
                            $this->main->result = $result;

                            //select 类型的需要显示select结果，所以直接返回，避免view被覆盖
                            return ;
                            break;

                        case 'update':
                            $result = Database::instance()->query(Database::UPDATE, $sql);
                            $this->set_status('success', 'Update success affect '.$result.' row');
                            break;

                        case 'insert':
                            $result = Database::instance()->query(Database::INSERT, $sql);
                            $this->set_status('success', 'Insert '.$result[1].' data successed');
                            break;

                        case 'delete':
                            $result = Database::instance()->query(Database::UPDATE, $sql);
                            $this->set_status('success', 'Delete data successed');
                            break;

                        default :
                            $this->set_status('error', 'Please selected the type');
                            break;
                    }

                }catch (Database_Exception $e){

                    $this->set_status('error', $e->getMessage());

                }

            } else {
                $this->set_status('error', 'Please Input the SQL');
            }
        }

        $this->main = View::factory('admin/tool/upgrade');
    }

    /**
     * 运行记录查看
     */
    public function action_logview(){

        $mode = $this->request->query('mode');
        $date = $this->request->query('date');
        if(!$date){
            $date = date('Y-m-d');
        }

        $level = $this->request->query('level', null);
        $this->main = View::factory('admin/tool/logview', $this->_getLogReport($date, $level, $mode));
    }

    /**
     * 删除运行记录
     */
    public function action_deletelog(){
        $date = $this->request->param('id');
        if($date){
            $log_file = $this->_getlogfile($date);
            if(file_exists($log_file)){
                unlink($log_file);
                $this->set_status('success', __('delete the log file successed'));
            }
        }else {
            $this->set_status('failed', 'File not found');
        }
        
        $this->redirect(URL::site('admin/tool/logview'));
    }

    /**
     * 缓存信息查看
     */
    public function action_cache(){
        $file_cache = Cache::instance('file');
        try{
            $memcache = Cache::instance('memcache');
        } catch(Cache_Exception $e) {
            $memcache = NULL;
        }


        if($this->request->query('ac') == 'flush'){

            $driver = $this->request->query('driver');
            switch($driver){
                case 'file':
                    $file_cache->delete_all();
                    break;

                case 'memcache':
                    if($memcache instanceof Cache_Memcache){
                        $memcache->delete_all();
                    } else {

                        $this->set_status('error', 'Memcache is not found');
                        return ;
                    }

                    break;

                case 'all':
                    if($memcache instanceof Cache_Memcache){
                        $memcache->delete_all();
                    }
                    $file_cache->delete_all();
                    break;
            }

            $this->set_status('success');
        }

        $this->main = View::factory('admin/tool/cache')->bind('file_cache', $file_cache)->bind('memcache', $memcache);
    }

    private function _getlogfile($date){
        $path = str_replace('-', DIRECTORY_SEPARATOR, $date);
        return APPPATH . 'logs' . "/{$path}.php";
    }

    private function _getLogReport($date, $level = null, $mode = null) {
        $filePath = $this->_getlogfile($date);
        $logsEntries = array();
        if(file_exists($filePath)){
            $Report = new Model_Logreport($filePath);
            $logsEntries = $Report->getLogsEntries();

            unset($Report, $filePath);

            if($level) {
                foreach($logsEntries as $k => $entry){
                    if(Arr::get($entry, 'level') != $level) unset($logsEntries[$k]);
                }
            }

        }
        return array(
            'logs' => $logsEntries,
            'date' => $date,
            'log_level' => $level,
            'mode' => $mode,
        );
    }
}
