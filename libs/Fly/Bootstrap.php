<?php
/**
 * Bootstrap.php
 *
 * Core Bootstrap class
 *
 * @author      Nguyen Duy <nguyenducduy.it@gmail.com>
 * @since       2014-12-19
 * @category    Fly
 *
 */

namespace Fly;

use Phalcon\Mvc\Model\MetaData\Files as PhMetadataFiles;
use Phalcon\Acl\Adapter\Memory as PhAcl;
use Phalcon\Annotations\Adapter\Files as PhAnnotationsAdapter;
use Phalcon\Cache\Backend\File as PhCacheBack;
use Phalcon\Cache\Frontend\Data as PhCacheFront;
use Phalcon\Config as PhConfig;
use Phalcon\Crypt as PhCrypt;
use Phalcon\Db\Adapter\Pdo\Mysql as PhMysql;
use Phalcon\Events\Manager as PhEventsManager;
use Phalcon\Flash\Session as PhFlash;
use Phalcon\Flash\Session as PhFlashSession;
use Phalcon\Http\Response\Cookies as PhCookies;
use Phalcon\Loader as PhLoader;
use Phalcon\Logger\Adapter\Database as PhLoggerDatabase;
use Phalcon\Mvc\Application as PhApplication;
use Phalcon\Mvc\Dispatcher as PhDispatcher;
use Phalcon\Mvc\Model\Manager as PhModelsManager;
use Phalcon\Mvc\Router as PhRouter;
use Phalcon\Mvc\Url as PhUrl;
use Phalcon\Mvc\View as PhView;
use Phalcon\Mvc\View\Engine\Volt as PhVolt;
use Phalcon\Queue\Beanstalk\Extended as PhExtended;
use Phalcon\Security as PhSecurity;
use Phalcon\Session\Adapter\Files as PhSession;
use Uploader\Uploader as Uploader;
use Fabfuel\Prophiler\Profiler as FaProfiler;
use Fly\AnnotationsInitializer as FlyAnnotationsInitializer;
use Fly\AnnotationsMetaDataInitializer as FlyAnnotationsMetaDataInitializer;
use Fly\Authentication as FlyAuthentication;
use Fly\Translate\Adapter\Native as FlyTranslate;
use League\Flysystem\Adapter\Local as FlyLocalAdapter;
use League\Flysystem\Filesystem as FlySystem;

class Bootstrap
{
    private $di;

    /**
     * Constructor
     *
     * @param $di
     */
    public function __construct($di)
    {
        $this->di = $di;
    }

    /**
     * Runs the application performing all initializations
     *
     * @param $options
     *
     * @return mixed
     */
    public function run($options)
    {
        $loaders = [
            'config',
            'loader',
            'session',
            'permission',
            'url',
            'database',
            'logger',
            'environment',
            'flash',
            'flashsession',
            'router',
            'dispatcher',
            'modelsmanager',
            'metadata',
            'annotations',
            'view',
            'cache',
            'security',
            'crypt',
            'cookie',
            'beanstalkd',
            'acl',
            'filemanager',
            'authentication'
        ];

        foreach ($loaders as $service) {
            $function = 'init' . ucfirst($service);
            $this->$function($options);
        }

        $application = new PhApplication();
        $application->setDI($this->di);

        $modules = $this->getModules();
        $application->registerModules($modules);

        $eventsManager = new PhEventsManager();
        $application->setEventsManager($eventsManager);
        $eventsManager->attach('application:beforeHandleRequest',function($event, $application) {
            $config = $this->di->get('config');
            $response = $this->di->get('response');
            $dispatcher = $this->di->get('dispatcher');
            $cookie = $this->di->get('cookie');

            //Detect mobile device
            $detect = new \Fly\Mobile_Detect();
            if ($config->app_mobile == true && $detect->isMobile() && SUBDOMAIN != 'm' && $dispatcher->getModuleName() == 'common') {
                //begin redirect link to mobile version
                $curPageURL = \Fly\Helper::getCurrentUrl();
                $curPageURL = str_replace(array('http://', 'https://'), array('http://m.', 'https://m.'), $curPageURL);

                $response->redirect($curPageURL, true);
            }

            //Setting language service
            $this->di->setShared('lang', function() use ($dispatcher, $cookie) {
                $language = '';

                // Detect language via cookie
                if ($cookie->has('language')) {
                    $language = $cookie->get('language')->getValue();
                } else {
                    //Get default language
                    $language = $this->config->defaultLanguage;
                }

                return new FlyTranslate([
                    'module' => strtolower($dispatcher->getModuleName()),
                    'controller' => $dispatcher->getControllerName(),
                    'language' => $language
                ]);
            });
        });

        return $application->handle()->getContent();
    }

    /**
     * Initializes the config. Reads it from its location and
     * stores it in the Di container for easier access
     *
     * @param array $options
     */
    public function initConfig($options = [])
    {
        $configFile  = require(ROOT_PATH . '/conf/global.php');

        $this->di->setShared('config', function () use ($configFile) {
            return new PhConfig($configFile);
        });
    }

    /**
     * Initializes the ACL Variable
     *
     * @param array $options
     */
    public function initPermission($options = [])
    {
        $permFile  = require(ROOT_PATH . '/conf/permission.php');

        $this->di->setShared('permission', function () use ($permFile) {
            $perm = new PhConfig($permFile);
            return $perm->toArray();
        });
    }

    /**
     * Initializes the environment
     *
     * @param array $options
     */
    public function initEnvironment($options = [])
    {
        if (FLAG_DEBUG) {
            ini_set('display_errors', true);
            error_reporting(E_ERROR | E_WARNING | E_PARSE);

            // Register Whoops Exception Handler
            new \Whoops\Provider\Phalcon\WhoopsServiceProvider();
        } else {
            ini_set('display_errors', false);
            error_reporting(-1);
        }
    }

    /**
     * Initializes the loader
     *
     * @param array $options
     */
    public function initLoader($options = [])
    {
        $config = $this->di['config'];

        // Creates the autoloader
        $loader = new PhLoader();
        $loader->registerNamespaces(
            [
                'Fly' => $config->app_path->libs,
                'Model' => $config->app_path->models,
                'Whoops' => $config->app_path->exceptionHandler,
                'Phalcon' => $config->app_path->incubator,
                'Foolz' => $config->app_path->sphinxql,
                'League' => $config->app_path->filemanager
            ]
        );

        $loader->register();

        // Dump it in the DI to reuse it
        $this->di['loader'] = $loader;
    }

    /**
     * Initializes the flash messenger
     *
     * @param array $options
     */
    public function initFlash($options = [])
    {
        $this->di->setShared('flash', function () {
            $params = [
                'error'   => 'alert alert-danger',
                'success' => 'alert alert-success',
                'notice'  => 'alert alert-info',
            ];

            return new PhFlash($params);
        });
    }

    /**
     * Initializes the flash session (using flash for redirect page)
     *
     * @param array $options
     */
    public function initFlashsession($options = [])
    {
        $this->di->setShared('flashSession', function () {
            $flashSession = new PhFlashSession([
                'error' => 'alert alert-danger',
                'success' => 'alert alert-success',
                'notice' => 'alert alert-info',
                'warning' => 'alert alert-warning'
            ]);

            return $flashSession;
        });
    }

    /**
     * Initializes the baseUrl
     *
     * @param array $options
     */
    public function initUrl($options = [])
    {
        $config = $this->di['config'];
        $request = $this->di['request'];
        /**
         * The URL component is used to generate all kind of urls in the
         * application
         */
        $this->di->setShared('url', function () use ($config, $request) {
            $url = new PhUrl();

            if (SUBDOMAIN == 'm') {
                $config->app_baseUri = 'm.' . $config->app_baseUri;
            }

            $url->setBaseUri($request->getScheme() . '://' . $config->app_baseUri . '/');
            $url->setStaticBaseUri($request->getScheme() . '://' . $config->app_resourceUri . '/');
            return $url;
        });
    }

    /**
     * Initializes the router
     *
     * @param array $options
     */
    public function initRouter($options = [])
    {
        $config = $this->di['config'];
        $loader = $this->di['loader'];

        $this->di->setShared('router', function () use ($config, $loader) {
            $router = new PhRouter(false);
            $router->setDefaultModule('common');

            foreach ($config['app_routes'] as $route => $params) {
                $router->add($route, (array) $params)->setHostName($config->app_baseUri);
            }

            if (SUBDOMAIN == 'm') {
                $router->setDefaultModule('mobile');
                $mobileRoutes = [
                    '/:controller' => [
                        'module'     => 'mobile',
                        'controller' => 1,
                    ],
                    '/:controller/:action/:params' => [
                        'module'     => 'mobile',
                        'controller' => 1,
                        'action'     => 2,
                        'params'     => 3,
                    ],
                    '/' => [
                        'module'     => 'mobile',
                        'controller' => 'index',
                        'action' => 'index',
                    ]
                ];

                foreach ($mobileRoutes as $route => $params) {
                    $router->add($route, (array) $params)->setHostName('m.' . $config->app_baseUri);
                }
            }

            $router->removeExtraSlashes(true);

            return $router;
        });
    }

    /**
     * Initializes the dispatcher
     *
     * @param array $options
     */
    public function initDispatcher($options = [])
    {
        $di = $this->di;

        $di->set('dispatcher', function($di) {
            $evManager = new PhEventsManager();

            $dispatcher = new \Phalcon\Mvc\Dispatcher();
            $dispatcher->setEventsManager($evManager);

            return $dispatcher;
        }, true);
    }

    /**
     * Initializes the view and Volt
     *
     * @param array $options
     */
    public function initView($options = [])
    {
        $di     = $this->di;
        $config = $di['config'];

        $this->di['volt'] = function ($view, $di) use ($config) {
            $volt  = new PhVolt($view, $di);
            $volt->setOptions(
                [
                    'compiledPath'      => $config->app_volt->path,
                    'compiledExtension' => $config->app_volt->extension,
                    'compiledSeparator' => $config->app_volt->separator,
                    'compileAlways'     => true,
                ]
            );

            $compiler = $volt->getCompiler();
            $compiler->addFilter('floor', 'floor');
            $compiler->addFunction('range', 'range');

            return $volt;
        };

        /**
         * Setup the view service
         */
        $this->di['view'] = function () {

            $view = new PhView();
            $view->registerEngines(['.volt' => 'volt']);

            return $view;
        };
    }

    /**
     * Initializes the logger
     *
     * @param array $options
     */
    public function initLogger($options = [])
    {
        $db = $this->di->get('db');

        $this->di->setShared('logger', function () use ($db) {
           return new PhLoggerDatabase('errors', array(
                'db' => $db,
                'table' => 'fly_logs'
            ));
        });
    }

    /**
     * Initializes the database and metadata adapter
     *
     * @param array $options
     */
    public function initDatabase($options = [])
    {
        $di     = $this->di;
        $config = $di['config'];

        $this->di['db'] = function () use ($config, $di) {
            // if (FLAG_DEBUG) {
            //     // $logger        = $di['logger'];
            //     $eventsManager = new PhEventsManager();

            //     // Listen all the database events
            //     $eventsManager->attach(
            //         'db',
            //         function ($event, $connection) use ($logger) {
            //             if ($event->getType() == 'beforeQuery') {
            //                 $sqlStatement = $connection->getSQLStatement();

            //                 // $logger->log(
            //                 //     $sqlStatement,
            //                 //     PhLogger::INFO
            //                 // );
            //             }
            //         }
            //     );
            // }

            $params = [
                "host"     => $config->app_db->host,
                "username" => $config->app_db->username,
                "password" => $config->app_db->password,
                "dbname"   => $config->app_db->name,
                "charset"  => 'utf8',
            ];

            $conn = new PhMysql($params);

            // Set everything to UTF8
            // $conn->execute('SET NAMES UTF8', []);

            // if (FLAG_DEBUG) {
            //     // Assign the eventsManager to the db adapter instance
            //     $conn->setEventsManager($eventsManager);
            // }

            return $conn;
        };
    }

    /**
     * Initializes the models Metadata and Annotation Strategy
     */
    public function initMetadata($options = [])
    {
        $config = $this->di['config'];

        $this->di->set('modelsMetadata', function() use ($config) {
            $metaData = new PhMetadataFiles(
                ['metaDataDir' => ROOT_PATH . $config->app_model->metadata]
            );

            //Set a custom meta-data database introspection
            $metaData->setStrategy(new FlyAnnotationsMetaDataInitializer());

            return $metaData;
        });
    }

    /**
     * Initializes the session
     *
     * @param array $options
     */
    public function initSession($options = [])
    {
        $this->di->setShared('session', function () {
            $session = new PhSession();
            $session->start();

            return $session;
        });
    }

    /**
     * Initializes the cache
     *
     * @param array $options
     */
    public function initCache($options = [])
    {
        $config = $this->di['config'];

        $this->di->setShared('cache', function () use ($config) {
            // Get the parameters
            $lifetime        = $config->app_cache->lifetime;
            $cacheDir        = $config->app_cache->path;
            $frontEndOptions = ['lifetime' => $lifetime];
            $backEndOptions  = ['cacheDir' => $cacheDir];

            $frontCache = new PhCacheFront($frontEndOptions);
            $cache      = new PhCacheBack($frontCache, $backEndOptions);

            return $cache;
        });
    }

    /**
     * Initializes the Security component
     *
     * @param array $options
     */
    public function initSecurity($options = [])
    {
        $this->di->setShared('security', function () {
            $security = new PhSecurity();
            $security->setWorkFactor(10);

            return $security;
        });
    }

    /**
     *  Initializes Crypt
     */
    public function initCrypt($options = [])
    {
        $config = $this->di['config'];

        $this->di->set('crypt', function () use ($config) {
            $crypt = new PhCrypt();
            $crypt->setMode(MCRYPT_MODE_CFB);
            $crypt->setKey($config->app_crypt->encryptionkey);

            return $crypt;
        });
    }

    /**
     * Initializes the models manager
     */
    public function initModelsmanager($options = [])
    {
        $this->di->set('modelsManager', function() {
            $eventsManager = new PhEventsManager();
            $modelsManager = new PhModelsManager();
            $modelsManager->setEventsManager($eventsManager);

            //Attach a listener to models-manager
            $eventsManager->attach('modelsManager', new FlyAnnotationsInitializer());

            return $modelsManager;
        });
    }

    /**
     * Initializes the model annotations store location
     */
    public function initAnnotations($options = [])
    {
        $config = $this->di['config'];

        $this->di->set('annotations', function() use ($config) {
            return new PhAnnotationsAdapter([
                'annotationsDir' => $config->app_annotations->cache
            ]);
        });
    }

    /**
     * Initializes the cookie
     */
    public function initCookie($options = [])
    {
        $this->di->setShared('cookie', function() {
            $cookie = new PhCookies();
            $cookie->useEncryption(true);

            return $cookie;
        });
    }

    /**
     * Initialized the mesage queue system Beanstalkd
     */
    public function initBeanstalkd($options = [])
    {
        $config = $this->di['config'];

        $this->di->setShared('beanstalkd', function () use ($config){
            //Connect to the queue
            $beanstalkd = new PhExtended([
                'host' => $config->app_beanstalkd->host,
                'port' => $config->app_beanstalkd->port,
                'prefix' => $config->app_beanstalkd->prefix
            ]);

            return $beanstalkd;
        });
    }

    /**
     * Initialized the ACL service
     */
    public function initAcl($options = [])
    {
        $this->di->setShared('acl', function() {
            $acl = new PhAcl();
            $acl->setDefaultAction(\Phalcon\Acl::DENY);

            return $acl;
        });
    }

    /*
     * Initialized the Uploader Service
     */
    public function initUploader($options = [])
    {
        $config = $this->di['config'];
        $this->di->setShared('uploader', function() use ($config) {
            $uploader = new Uploader();

            return $uploader;
        });
    }

    /**
     * Initialized the File system
     */
    public function initFilemanager($options = [])
    {
        $this->di->setShared('filemanager', function() {
            $cache = null;
            $filesystem = new FlySystem(
                new FlyLocalAdapter(ROOT_PATH),
                $cache
            );

            return $filesystem;
        });
    }

    /**
     * Initialized the Authentication system
     */
    public function initAuthentication($options = [])
    {
        $this->di->setShared('authentication', function() {
            return new FlyAuthentication();
        });
    }

    /**
     * Private function to get the modules array
     */
    private function getModules()
    {
        $config = $this->di['config'];

        $modules = $config->app_modules;

        $appModules = [];

        foreach ($modules as $key => $module) {

            $appModules[$key] = [
                'className' => $module->className,
                'path'      => $module->path,
            ];

        }

        // Register the installed modules
        return $appModules;
    }
}
