<?php

/**
 * Access Controll List (ACL) Config Variable for Core Framework
 * @var array
 */
define('ROLE_GUEST', 1);
define('ROLE_ADMIN', 5);
define('ROLE_MOD', 10);
define('ROLE_MEMBER', 15);

return [
    ROLE_GUEST => [
        'Admin' => array (
            'login:*',
            'notfound:*',
        ),
        'Common' => array (
            'index:*',
            'install:*',
        ),
    ],

    ROLE_ADMIN => [
        'Admin' => array (
            'index:*',
            'login:*',
            'dashboard:*',
            'codegenerator:*',
            'logs:*',
            'user:*',
            'notfound:*',
        ),
        'Common' => array (
            'index:*',
            'user:*',
            'install:*',
        ),
    ],

    ROLE_MOD => [
        'Admin' => array (
            'index:*',
            'login:*',
            'dashboard:*',
            'user:*',
        ),
        'Common' => array (
            'index:*',
            'user:*',
        ),
    ],

    ROLE_MEMBER => [
        'Admin' => array (
            'index:*',
        ),
        'Common' => array (
            'index:*',
        ),
    ],
];
