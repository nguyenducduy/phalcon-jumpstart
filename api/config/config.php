<?php

/**
 * Settings to be stored in dependency injector
 */

$settings = array(
	'database' => [
		'adapter' => 'Mysql',	/* Possible Values: Mysql, Postgres, Sqlite */
		'host' => 'localhost',
		'username' => 'root',
		'password' => 'root',
		'name' => 'myblog',
		'port' => 3306
	],
    'app_annotations' => [
        'cache' => ROOT_PATH . '/../cache/annotations/',
    ],
    'app_model' => [
        'metadata' => ROOT_PATH . '/../cache/metadata/',
    ]
);


return $settings;
