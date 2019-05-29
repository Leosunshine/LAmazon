<?php

defined('APP_PATH') || define('APP_PATH', realpath('.'));



return new \Phalcon\Config(array(
    'database' => array(
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => 'lamazon',
        'password'    => '123456',
        'dbname'      => 'lamazon',
        'charset'     => 'utf8',
    ),
    'application' => array(
        'controllersDir' => APP_PATH . '/app/controllers/',
        'modelsDir'      => APP_PATH . '/app/models/',
        'migrationsDir'  => APP_PATH . '/app/migrations/',
        'viewsDir'       => APP_PATH . '/app/views/',
        'pluginsDir'     => APP_PATH . '/app/plugins/',
        'libraryDir'     => APP_PATH . '/app/library/',
        'cacheDir'       => APP_PATH . '/app/cache/',
        'baseUri'        => '/',
        'AmazonModel'    => APP_PATH.'/app/library/Model',
    ),
    'libraryDirs' => array(
        APP_PATH.'/app/library/',
        APP_PATH.'/app/library/MarketplaceWebService/',
        APP_PATH.'/app/library/MarketplaceWebService/Model/'
    ),
    'AmazonRoot' => APP_PATH."/app/library/"
));
