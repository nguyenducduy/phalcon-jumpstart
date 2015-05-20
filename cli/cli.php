<?php

error_reporting(E_ALL);
use Phalcon\Mvc\Model\MetaData\Files as PhMetadataFiles;
use Fly\AnnotationsInitializer as FlyAnnotationsInitializer;
use Fly\AnnotationsMetaDataInitializer as FlyAnnotationsMetaDataInitializer;
use League\Flysystem\Adapter\Local as FlyLocalAdapter;
use League\Flysystem\Filesystem as FlySystem;
use Phalcon\Annotations\Adapter\Files as PhAnnotationsAdapter;
use Phalcon\CLI\Console as PhConsole;
use Phalcon\Config as PhConfig;
use Phalcon\Db\Adapter\Pdo\Mysql as PhMysql;
use Phalcon\DI\FactoryDefault\CLI as PhCliDI;
use Phalcon\Events\Manager as PhEventsManager;
use Phalcon\Logger as PhLogger;
use Phalcon\Logger\Adapter\File as PhLoggerFile;
use Phalcon\Logger\Formatter\Line as PhLoggerFormatter;
use Phalcon\Mvc\Model\Manager as PhModelsManager;
use Phalcon\Logger\Adapter\Database as PhLoggerDatabase;

try {

    if (!defined('ROOT_PATH')) {
        define('ROOT_PATH', dirname(dirname(__FILE__)));
    }

    require_once ROOT_PATH . '/cli/app/Migration.php';

        /**
     * Cli DI
     */
    $di = new PhCliDI();

    /**
     * CONFIG
     */
    $configFile   = require(ROOT_PATH . '/conf/global.php');
    $config       = new PhConfig($configFile);
    $di['config'] = $config;

    if (isset($config->app_debug)) {
        $debug = (bool) $config->app_debug;
    } else {
        $debug = false;
    }

    /**
     * AUTOLOADER
     */
    $dirs  = [ROOT_PATH . '/cli/app/tasks'];
    $loader = new \Phalcon\Loader();
    $loader->registerNamespaces([
        'Fly' => $config->app_path->libs,
        'Model' => $config->app_path->models,
        'Phalcon' => $config->app_path->incubator,
        'Foolz' => $config->app_path->sphinxql,
        'PhpSmug' => $config->app_path->phpsmug,
        'League' => $config->app_path->filemanager
    ]);
    $loader->registerDirs($dirs);
    $loader->register();

    /**
     * LOGGER
     */
    $di['logger'] = function () use ($config) {
        $name   = '/migration-' . date('Ymd') . '.log';
        $logger    = new PhLoggerFile(ROOT_PATH . '/logs/mig' . $name);
        $formatter = new PhLoggerFormatter($config->app_logger->format);
        $logger->setFormatter($formatter);

        return $logger;
    };

    /**
     * DATABASE
     */
    $di['db'] = function () use ($config, $di) {
        $logger        = $di['logger'];
        $eventsManager = new PhEventsManager();

        // Listen all the database events
        $eventsManager->attach(
            'db',
            function ($event, $connection) use ($logger) {
                if ($event->getType() == 'beforeQuery') {
                    $logger->log(
                        $connection->getSQLStatement(),
                        PhLogger::INFO
                    );
                }
            }
        );

        $params = [
            "host"     => $config->app_db->host,
            "username" => $config->app_db->username,
            "password" => $config->app_db->password,
            "dbname"   => $config->app_db->name,
            "charset"  => 'utf8',
        ];

        $conn = new PhMysql($params);

        // Set everything to UTF8
        $conn->execute('SET NAMES UTF8', []);

        $conn->setEventsManager($eventsManager);

        return $conn;
    };

    /**
     * If the configuration specify the use of metadata adapter use it
     * or use memory otherwise
     */
    $di['modelsMetadata'] = function () use ($config) {
        $metaData = new PhMetadataFiles(
            ['metaDataDir' => ROOT_PATH . $config->app_model->metadata]
        );

        //Set a custom meta-data database introspection
        $metaData->setStrategy(new FlyAnnotationsMetaDataInitializer());

        return $metaData;
    };

    $di['modelsManager'] = function() {
        $eventsManager = new PhEventsManager();
        $modelsManager = new PhModelsManager();
        $modelsManager->setEventsManager($eventsManager);

        //Attach a listener to models-manager
        $eventsManager->attach('modelsManager', new FlyAnnotationsInitializer());

        return $modelsManager;
    };

    $di['annotations'] = function() use ($config) {
        return new PhAnnotationsAdapter([
            'annotationsDir' => $config->app_annotations->cache
        ]);
    };

    $di['filemanager'] = function() use ($config){
        $cache = null;
        $filesystem = new FlySystem(
            new FlyLocalAdapter(ROOT_PATH),
            $cache
        );

        return $filesystem;
    };

    $di['lang'] = function() {
        return new \stdClass();
    };

    $di['loggerDB'] = function() use ($di) {
        $db = $di->get('db');

        return new PhLoggerDatabase('errors', array(
            'db' => $db,
            'table' => 'fly_logs'
        ));
    };

    /**
     * File location constants
     */
    if (!defined('JSON_STRUCTURE')) {
        define('JSON_STRUCTURE', ROOT_PATH . '/migration/structure.json');
    }
    if (!defined('JSON_DATA')) {
        define('JSON_DATA', ROOT_PATH . '/migration/data.json');
    }

    /**
     * CONSOLE APPLICATION
     */
    $console = new PhConsole();
    $console->setDI($di);

    /**
     * Make sure that the migration class is loaded
     */
    $migration = new \FlyCli\Migration($di['db']);

    /**
     * Process the console arguments
     */
    $arguments = [];
    $params    = [];

    foreach ($argv as $key => $argument) {
        if ($key == 1) {
            $arguments['task'] = $argument;
        } elseif ($key == 2) {
            $arguments['action'] = $argument;
        } elseif ($key >= 3) {
            $params[] = $argument;
        }
    }

    if (count($params) > 0) {
        $arguments['params'] = $params;
    }

//    // define global constants for the current task and action
//    define('CURRENT_TASK', (isset($argv[1]) ? $argv[1] : null));
//    define('CURRENT_ACTION', (isset($argv[2]) ? $argv[2] : null));

    // handle incoming arguments
    $console->handle($arguments);

} catch (\Phalcon\Exception $e) {

    echo $e->getMessage() . "\n";
    echo $e->getTraceAsString();
    exit(255);

}
