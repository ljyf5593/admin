<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Create report from a generic Kohana Log file
 *
 * Author: Anis uddin Ahmad <anisniit@gmail.com>
 * Created On: 11/10/11 8:44 PM
 */
class Model_Logreport{

    protected $_rawContent;
    protected $_logEntries = array();

    // Copy of Kohana_log_file log levels
    public static $levels = array(
        'WARNING' => 'warning',
        'ALERT' => 'warning',
        'DEBUG' => 'warning',

        'ERROR' => 'important',
        'CRITICAL' => 'important',
        'EMERGENCY' => 'important',

        'NOTICE' => 'notice',

        'INFO' => 'success',
    );

    function __construct($filepath)
    {
        // Read lines as array. Skip first 2 lines - SYSPATH checking and blank line
        $this->_rawContent = array_slice(file($filepath), 2);
        $this->_createLogEntries();
    }

    public function getLogsEntries($level = null){
        return $this->_logEntries;
    }

    protected function _createLogEntries()
    {
        $pattern = "/(.*) --- ([A-Z]*): ([^:]*):? ([^~]*)~? (.*)/";

        //匹配自定义log
        $custom_pattern = "/(.*) --- ([A-Z]*): (.*)/";

        $last_log = null;
        $i = 0;
        foreach($this->_rawContent as $logRaw) {
            $logRaw = trim($logRaw);
            if ($logRaw != '--' && (isset($logRaw[0]) && $logRaw[0] != '#') && stripos($logRaw, 'STRACE') === FALSE) {
                preg_match($pattern, $logRaw, $matches);

                $log = array();
                $log['raw'] = $logRaw;
                if($matches) {
                    $log['time'] = strtotime($matches[1]);
                    $log['level'] = $matches[2];    // Notice, Error etc.
                    $log['style'] = $this->_getStyle($matches[2]);    // CSS class for styling
                    $log['type'] = $matches[3];     // Exception name
                    $log['message'] = $matches[4];
                    $log['file'] = $matches[5];

                } else { //如果是自定义Log
                    preg_match($custom_pattern, $logRaw, $matches);
                    if($matches) {
                        $log['time'] = strtotime($matches[1]);
                        $log['level'] = $matches[2];    // Notice, Error etc.
                        $log['style'] = $this->_getStyle($matches[2]);    // CSS class for styling
                        $log['type'] = 'Custom';     // Exception name
                        $log['message'] = $matches[3];
                        $log['file'] = '';
                    }
                }

                $this->_logEntries[] = $log;
                $last_log = $i;
                $i++;
            }

            if (!isset($this->_logEntries[$last_log]['message'])){
                $this->_logEntries[$last_log]['message'] = '';
            }

            if (stripos($logRaw, 'STRACE') !== FALSE) {
                $message = Arr::get($this->_logEntries[$last_log], 'message');
                $this->_logEntries[$last_log]['message'] =  $message . '<br/><br/><p>Stack Trace:</p><ol style="font-family:consolas;font-size:8pt">';
            }

            if (isset($logRaw[0]) && $logRaw[0] == '#') {
                $logRaw = preg_replace('/#\d /', '', $logRaw);
                $this->_logEntries[$last_log]['message'] .= '<li>'.$logRaw . '</li>';
            }

            if (preg_match('/\{main\}/', $logRaw)) {
                $this->_logEntries[$last_log]['message'] .= '</ol>';
            }
        }
    }

    private function _getStyle($level)
    {
        return isset(self::$levels[$level]) ? self::$levels[$level] : 'info';
    }

}