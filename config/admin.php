<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'top_nav' => array(
		'user' => array(),
		'setting' => array(
			'icon' => 'icon-wrench',
		),
		'permission' => array(
			'icon' => 'icon-signin',
		),
		'tool' => array(
			'icon' => 'icon-magic',
			'sub_nav' => array(
				'backup' => array(
					'icon' => 'icon-share',
				),
				'logview' => array(
					'icon' => 'icon-dashboard',
				),
				'cache' => array(
					'icon' => 'icon-bar-chart',
				),
			),
		),
	),

	'side_nav' => array(
		'resource' => array(
			'icon' => 'icon-paper-clip',
		),
		'faq' => array(
			'icon' => 'icon-twitter',
		),
	),
);