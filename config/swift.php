<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'default' => array(
		'transport' => array(
			'type' => Email::TRANSPORT_SMTP,
			'options' => array(
				'host' => '',
				'port' => '',
				'encryption' => '',
				'timeout' => '',
				'username'	=> '',
				'password' => '',			
			),
		),
	),
);