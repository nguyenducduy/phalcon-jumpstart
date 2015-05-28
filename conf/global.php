<?php

/**
 * Global Config Variable for Core Framework
 * @var array
 */
$base = [
    'app_unique'      => 'pj-de41dc25-d996-4c40-9818-61af1c375629', //using for cache key per website
    'app_mobile'      => false,  //Mobile Friendly
    'app_version'     => '1.0',
    'app_name'        => 'Phalcon Jumpstart',
    'app_baseUri'     => 'newpj.site',
    'app_resourceUri' => 'newpj.site',
    'app_db'          => [
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => 'root',
        'password'    => 'root',
        'name'        => 'phalconjumpstart',
        'prefix'      => 'fly_'
    ],
    'app_sphinx' => [
        'host' => 'localhost',
        'realtime_port' => '9306',
        'plain_port' => '9312'
    ],
    'app_beanstalkd' => [
        'host' => 'localhost',
        'port' => '11300'
    ],
    'cssVersion' => 4,
    'jsVersion' => 4,
    'defaultTemplate' => 'default',
    'defaultLanguage' => 'en'
];

$path = [
    'app_path' => [
        'libs'              => ROOT_PATH . '/libs/Fly/', //End with slashes
        'models'            => ROOT_PATH . '/models/',
        'views'             => ROOT_PATH . '/modules/common/views/',
        'exceptionHandler'  => ROOT_PATH . '/libs/Whoops/',
        'incubator'         => ROOT_PATH . '/libs/Phalcon/',
        'filemanager'       => ROOT_PATH . '/libs/League/',
        'uploader'          => ROOT_PATH . '/libs/Uploader',
    ]
];

$volt = [
    'app_volt' => [
        'path'      => ROOT_PATH . '/cache/volt/',
        'extension' => '.php',
        'separator' => '%%',
    ]
];

$cache = [
    'app_cache' => [
        'path'     => ROOT_PATH . '/cache/',
        'lifetime' => 3600,
    ]
];

$annotations = [
    'app_annotations' => [
        'cache' => ROOT_PATH . '/cache/annotations/'
    ]
];

$crypt = [
    'app_crypt' => [
        'encryptionkey'   => 'KkX+DVfEA>196yN'
    ]
];

$logger = [
    'app_logger' => [
        'adapter' => 'File',
        'path'    => ROOT_PATH . '/logs/app/',
        'format'  => '[%date%][%type%] %message%',
    ]
];

$model = [
    'app_model' => [
        'metadata' => '/cache/metadata/',
    ]
];

//Array variable to register modules in application container
$modules = [
    'app_modules' => [
        'common'   => [
            'className' => 'Module\Common',
            'path'      => ROOT_PATH . '/modules/common/Module.php',
        ],
        'admin' => [
            'className' => 'Module\Admin',
            'path'      => ROOT_PATH . '/modules/admin/Module.php'
        ],
        'mobile' => [
            'className' => 'Module\Mobile',
            'path'      => ROOT_PATH . '/modules/mobile/Module.php'
        ]
    ]
];

$routes = [
    'app_routes' => [
        '/:controller'                 => [
            'module'     => 'common',
            'controller' => 1,
        ],
        '/:controller/:action/:params' => [
            'module'     => 'common',
            'controller' => 1,
            'action'     => 2,
            'params'     => 3,
        ],
        '/'                 => [
            'module'     => 'common',
            'controller' => 'index',
            'action' => 'index',
        ],
        '/admin'                 => [
            'module'     => 'admin',
            'controller' => 'index',
            'action' => 'index',
        ],
        '/admin/:controller' => [
            'module' => 'admin',
            'controller' => 1,
        ],
        '/admin/:controller/:action/:params' => [
            'module'     => 'admin',
            'controller' => 1,
            'action'     => 2,
            'params'     => 3,
        ],
        '/admin/logout' => [
            'module'     => 'admin',
            'controller' => 'index',
            'action' => 'logout'
        ],
        '/([0-9]{4})/([0-9]{2})/([0-9]{2})/:params' => [
            "module" => "common",
            "controller" => "post",
            "action"     => "show",
            "year"       => 1, // ([0-9]{4})
            "month"      => 2, // ([0-9]{2})
            "day"        => 3, // ([0-9]{2})
            "slug"     => 4,
        ],
    ]
];

$settings = [
    'user' => [
        'directory' => '/public/uploads/user/',
        'minsize' => 1000,
        'maxsize' => 1000000,
        'mimes' => [
            'image/gif',
            'image/jpeg',
            'image/png',
         ],
        'extensions' => [
            'gif',
            'jpeg',
            'jpg',
            'png',
        ],
        'sanitize' => true,
        'hash' => 'md5'
    ],
    'post' => [
        'directory' => '/public/uploads/post/',
        'minsize' => 1000,
        'maxsize' => 1000000,
        'mimes' => [
            'image/gif',
            'image/jpeg',
            'image/png',
         ],
        'extensions' => [
            'gif',
            'jpeg',
            'jpg',
            'png',
        ],
        'sanitize' => true,
        'hash' => 'md5'
    ]
];

return array_merge(
    $base,
    $path,
    $volt,
    $cache,
    $crypt,
    $logger,
    $model,
    $modules,
    $routes,
    $annotations,
    $settings
);
