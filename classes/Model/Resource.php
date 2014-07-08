<?php
/**
 * 附件资源Model
 *
 * @author  Jie.Liu (ljyf5593@gmail.com)
 * @Id $Id: resource.php 39 2012-07-09 10:05:40Z Jie.Liu $
 */
class Model_Resource extends ORM{

	// 图片最大宽度和高度
	private $_maxWidth = 640;
	private $_maxHeight = 480;

	// 图片输出质量
	private $_quality = 80;

	protected $_search_row = array(
		'title',
	);

	protected $_list_row = array(
		'id',
		'title',
		'filename',
		'attachment',
		'dateline',
		'ordering',
	);

	public function get_attachment() {
		return $this->attachment.'&nbsp;<a target="_blank" href="'.URL::site('/upload/'.$this->attachment).'"><img height="100" class="preview" src="'.URL::site('/upload/'.$this->attachment).'"><i class="icon-picture icon-green icon-large"></i></a>';
	}

	/**
	 * 文件上传
	 * @param array $file
	 * @param array $data
	 */
	public function uploadFile(array $file, array $data=NULL){
		$message = array();
		$message['error'] = 1;
		if (Upload::not_empty($file)){
			if (Upload::valid($file)){ //检查数据是否正常
				if (Upload::type($file, $this->getAllowType())){
					if ($file = $this->saveFile($file, $data)){
						$message['error'] = 0;
						$message['url'] = URL::site(Upload::$default_directory.'/'.$file['attachment'], TRUE);
					}
				}else {
					$message['message'] = '非法文件类型';
				}

			}else {
				$message['message'] = '文件数据错误，请重新选择上传';
			}
		}else {
			$message['message'] = '请选择文件';
		}

		return $message;
	}

	/**
	 * 删除数据的同时删除文件
	 * @see Kohana_ORM::delete()
	 */
	public function delete(){
		$filename = Upload::$default_directory.DIRECTORY_SEPARATOR.$this->attachment;
		if (file_exists($filename)){
			@unlink($filename);
		}
		parent::delete();
	}

	/**
	 * 文件保存,写入数据库
	 * @param array $file
	 * @param array $data
	 */
	private function saveFile(array $file, array $data=NULL){
		//创建文件上传路径
		$uploadDir = $this->uploadDir();
		$abs = Upload::$default_directory.DIRECTORY_SEPARATOR.$uploadDir;
		$isimage = Upload::image($file);
		if ($filename = Upload::save($file, NULL, $abs)){
			// 大图片自动缩放
			$this->resizeFile($filename);

			$defaultData = array();
			$filename = str_replace('\\', '/', $filename);
			$fileInfo = pathinfo($filename);
			$defaultData['title'] = isset($data['title'])?$data['title']:$fileInfo['basename'];
			$defaultData['filesize'] = $file['size'];
			$defaultData['isimage'] = $isimage;
			$defaultData['filename'] = $file['name'];
			$filePath = str_replace('\\', '/', $uploadDir);
			$defaultData['attachment'] = $filePath.$fileInfo['basename'];
			$defaultData['mime'] = $file['type'];
			$defaultData['filetype'] = $fileInfo['extension'];
			$defaultData['description'] = isset($data['description'])?$data['description']:'';
			$defaultData['dateline'] = $_SERVER['REQUEST_TIME'];
			$defaultData['userid'] = Auth::instance()->get_user();
			$this->values($defaultData)->save();
			return $defaultData;
		}
	}

	/**
	 * 自动缩放图片到允许的尺寸
	 */
	private function resizeFile($filename){
		$file = Image::factory($filename);
		if($file->width > $this->_maxWidth){
			$file->resize($this->_maxWidth, NULL)->save(NULL, $this->_quality);
		} elseif ($file->height > $this->_maxHeight){
			$file->resize(NULL, $this->_maxHeight)->save(NULL, $this->_quality);
		}
	}

	/**
	 * 获取允许上传的文件类型
	 * Enter description here ...
	 */
	private function getAllowType(){
		return array('jpg', 'png', 'gif');
	}

	/**
	 * 获取文件后缀名
	 * @param string $fileName
	 */
	private function getExt($fileName){
		return strtolower(end(explode('.', $fileName)));
	}

	/**
	 * 生成上传文件路径
	 */
	private function uploadDir(){

		//生成年月文件夹和日期文件夹
		$dir = date('Ym').DIRECTORY_SEPARATOR.date('d');
		$abs = Upload::$default_directory.DIRECTORY_SEPARATOR.$dir;
		if (!is_dir($abs)){
			mkdir($abs, 0777, TRUE);
		}

		return $dir.DIRECTORY_SEPARATOR;
	}

}