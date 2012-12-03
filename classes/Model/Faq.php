<?php
/**
 * 站点帮助模型
 */
class Model_Faq extends ORM {

	// 需要加载富文本的列
	public $editer_row = 'content';

	protected $_list_row = array('id', 'title', 'label');

	public function rules(){
		return array(
			'label' => array(
				array(array($this, 'unique'), array('label', ':value')),
			),
		);
	}

}