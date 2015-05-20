<?php

/**
 * Auto Load Class files by namespace
 *
 * @eg
 	'namespace' => '/path/to/dir'
 */

$autoload = [
	'Events\Api' => ROOT_PATH . '/library/events/api/',
	'Micro\Messages' => ROOT_PATH . '/library/micro/messages/',
	'Utilities\Debug' => ROOT_PATH . '/library/utilities/debug/',
	'Security\Hmac' => ROOT_PATH . '/library/security/hmac/',
	'Interfaces' => ROOT_PATH . '/library/interfaces/',
	'Exceptions' => ROOT_PATH . '/exceptions/',
	'Http\Responses' => ROOT_PATH . '/library/http/responses/',
	'Controllers' => ROOT_PATH . '/controllers/',
	'Fly' => ROOT_PATH . '/../libs/Fly/',
	'Model' => ROOT_PATH . '/../models/',
	'Phalcon\Behavior' => ROOT_PATH . '/../libs/Phalcon/Behavior/'
];

return $autoload;