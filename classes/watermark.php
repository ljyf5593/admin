<?php
class Watermark {

	/**
	 * 水印文件存放的文件夹
	 * @var string
	 */
	private $default_dir = 'watermark';

	/**
	 * 水印需要使用的缓存对象，用于缓存配置文件
	 * @var Cache
	 */
	private $cache = NULL;

	private static $instance = NULL;

	private $water_position = array(
		'left_top' => array(
			'checked' => '',
			'offset_x' => 0,
			'offset_y' => 0,
		),
		'right_top' => array(
			'checked' => '',
			'offset_x' => TRUE,
			'offser_y' => 0,
		),
		'center' => array(
			'checked' => '',
			'offset_x' => NULL,
			'offset_y' => NULL,
		),
		'left_bottom' => array(
			'checked' => '',
			'offset_x' => 0,
			'offset_y' => TRUE,
		),
		'right_bottom' => array(
			'checked' => '',
			'offset_x' => TRUE,
			'offset_y' => TRUE,
		)
	);

	/**
	 * 水印标签Image信息
	 * @var Image
	 */
	private $watermark = NULL;

	private $watermark_file = '/img/watermark';

	/**
	 * 获取一个水印处理单例
	 * @static
	 * @return Watermark
	 */
	public static function instance(){
		if( ! self::$instance instanceof self){
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct(){
		$this->cache = Cache::instance();

		if($watermark = Kohana::find_file('media', $this->watermark_file, 'png')){
			$this->watermark = Image::factory($watermark);
		} else {
			throw new View_Exception('The watermark file :file could not be found', array(
				':file' => $this->watermark_file,
			));
		}
	}

	private function __clone(){}

	/**
	 * 获取水印配置信息
	 */
	public function get_config(){
		$watermark_config = $this->cache->get('watermark_config');
		if( ! $watermark_config) {
			// 获取水印配置信息
			$dataConfig = new Config_Database_Reader();
			$watermark_config = $dataConfig->load('watermark');
			$this->cache->set('watermark_config', $watermark_config);
		}

		return $watermark_config;
	}

	/**
	 * 设置水印配置信息
	 */
	public function set_config($config){

		$dataConfig = new Config_Database_Writer();
		Kohana::$config->attach($dataConfig);
		$watermark = Kohana::$config->load('watermark');

		//保存数据
		foreach ($config as $key=>$value){
			$watermark[$key] = $value;
		}

		// 删除缓存信息
		$this->cache->delete('watermark_config');

	}

	/**
	 * 获取水印位置信息
	 * @param null $position
	 * @return array|mixed
	 */
	public function get_position($position = NULL){
		if($position === NULL){
			return $this->water_position;
		}

		return Arr::get($this->water_position, $position);
	}

	/**
	 * 水印预览
	 */
	public function preview(){
		$watermark_config = $this->get_config();
		if(Arr::get($watermark_config, 'status')){ // 检测是否开启水印
			$file_name = '/img/watermark_template';
			if($template_file = Kohana::find_file('media', $file_name, 'jpg')){

				$template = $this->run(Image::factory($template_file));

				$response = Request::$current->response();
				$response->headers('Content-Type', 'image/png');
				$response->headers('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
				$response->headers('Pragma', 'no-cache');
				$response->headers('Connection', 'close');

				echo $response->send_headers()->body($template->render());
				die();

			} else {
				throw new View_Exception('The template file :file could not be found', array(
					':file' => $template_file,
				));
			}

		} else {
			echo Request::$current->response()->send_headers()->body('未开启水印功能');die();
		}
	}

	/**
	 * 根据提供的文件，返回加了水印后的图片链接
	 * @param $image
	 * @param null $dir
	 * @return string
	 */
	public function watermark($image, $dir = NULL){

		// 水印图片的链接地址
		$watermark_url = $this->default_dir.'/'.$image;

		$watermark_file = str_replace('/', DIRECTORY_SEPARATOR, DOCROOT.$watermark_url);
		if(is_file($watermark_url)){ // 如果水印图片已经存在，则直接返回

			return $watermark_url;

		}else{

			$path_info = pathinfo($watermark_file);
			if(!is_dir($path_info['dirname'])){
				mkdir($path_info['dirname'], 0777, TRUE);
			}

			// 读取传入的图片
			if($dir != NULL){
				$template_file = $dir.'/'.$image;
			} else {
				$template_file = $image;
			}

			$template = $this->run(Image::factory(DOCROOT.$template_file));
			$template->save($watermark_file);
		}

		return $watermark_url;
	}

	/**
	 * 图片水印处理
	 * @param Image $template
	 * @return Image
	 */
	private function run(Image $template){
		$watermark_config = $this->get_config();
		$position = $this->get_position(Arr::get($watermark_config, 'position', 'center'));
		$template->watermark($this->watermark, $position['offset_x'], $position['offset_y'], Arr::get($watermark_config, 'opacity', 50));

		return $template;
	}
}