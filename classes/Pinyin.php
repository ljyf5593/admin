<?php defined('SYSPATH') or die('No direct script access.');

class Pinyin {
	// 数据文件的文件句柄
	private $file = NULL;

	// 数据库文件的文件名
	private $file_name = 'pinyin';

	/**
	 * @var Pinyin
	 */
	private static $instance = NULL;

	/**
	 * 单例模式
	 * @static
	 * @return null|Pinyin
	 */
	public static function instance(){
		if( ! self::$instance instanceof self){
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * 字符串转拼音，保留多音词版本
	 * @param string 汉字字符串
	 * @param string 拼音之间的分隔符
	 * @param string 多音字数字之间的分割
	 * @return string 
	 */
	public function str2multitone($str, $split = '', $splits = '|') {
		$result = $this->strconvert($str);
		$pinyinlist = array();
	        foreach($result as $multitone){
	            $temp = array();
	            foreach($multitone as $item){
	                if($pinyinlist){
	                    foreach($pinyinlist as $origin){
	                        $temp[] = $origin.$split.$item;
	                    }
	                }else{
	                    $temp[] = $item;
	                }
	            }
	            $pinyinlist = $temp;
	        }
		
		return implode($splits, $pinyinlist);
	}

	/**
	 * 字符串转拼音
	 * @param $str
	 * @return string
	 */
	public function str2pinyin($str, $split = ''){
		$result = $this->strconvert($str);
		$pinyin = array();
		foreach ($result as $item) {
			$pinyin[] = $item[0];
		}

		return implode($split, $pinyin);
	}

	/**
	 * 字符串转拼音数组
	 * @param string
	 * @return array
	 */
	public function strconvert($str) {
		$result = array();
		foreach($this->utf8_str_split($str) as $char){
			$pinyin = $this->get_pinyin($char);
			$result[] = explode(',', $pinyin);
		}
		return $result;
	}

	/**
	 * 获取汉字拼音的首字母
	 * @param $str
	 * @return string
	 */
	public function first_pinyin($str){
		$result = '';

		foreach(UTF8::str_split($str) as $char){
			$result .= substr($this->get_pinyin($char), 0, 1);
		}

		return $result;
	}

	/**
	 * 单个汉字转拼音
	 * @param $char
	 * @return string
	 */
	public function get_pinyin($char){

		if(strlen($char) === 3 && $this->file) { // 中文在utf-8编码中占用三个字节
			$offset = $this->word2dec($char);
			if($offset >= 0) {
				fseek($this->file, ($offset - 19968) << 4, SEEK_SET);
				return trim(fread($this->file, 16));
			}
		}

		return $char;
	}

	/**
	 * 汉字转十进制
	 * 汉字的二进制编码  1110xxxx 10xxxxxx 10xxxxxx
	 * @param $word
	 * @return number
	 */
	private function word2dec($word){

		return base_convert(bin2hex(iconv('utf-8', 'ucs-4', $word)), 16, 10);

	}

	private function __construct(){
		if($file_name = Kohana::find_file('data', $this->file_name, 'dat')){
			if(is_file($file_name)){
				$this->file = fopen($file_name, 'rb');
			} else {
				throw new Kohana_Exception('The file :file could not be read', array(
					':file' => $file_name,
				));
			}
		} else {
			throw new Kohana_Exception('The file :file could not be found', array(
				':file' => $this->file_name,
			));
		}
	}

	public function __destruct() {
		if($this->file){
			fclose($this->file);
		}
	}
}
