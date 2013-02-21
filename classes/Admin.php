<?php
class Admin {

	/**
	 * 从一个数组中获取消息，并返回符合后台模板的消息label
	 * @param $key
	 * @param $message
	 * @return array
	 */
	public static function get_message_label($key, $message){
		$label_class = '';
		// 查看该列是否有消息要显示
		if($key_message = Arr::get($message, $key)){
			$label_class = 'error';
			$message_label = "<span id=\"{$key}-label\" class=\"label label-important \">{$key_message}</span>";
		} else {
			$message_label = "<span id=\"{$key}-label\" class=\"label label-important hide\"></span>";
		}

		return array($message_label, $label_class);
	}
}