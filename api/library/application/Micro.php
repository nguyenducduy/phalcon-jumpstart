<?php

/**
 * Small Micro application to run simple/rest based applications
 *
 * @package Application
 * @author Jete O'Keeffe
 * @version 1.0
 * @link http://docs.phalconphp.com/en/latest/reference/micro.html
 * @example
 	$app = new Micro();
	$app->setConfig('/path/to/config.php');
	$app->setAutoload('/path/to/autoload.php');
	$app->get('/api/looks/1', function() { echo "Hi"; });
	$app->finish(function() { echo "Finished"; });
	$app->run();
 */

namespace Application;

use Interfaces\IRun as IRun;
use \Phalcon\Events\Manager as PhEventsManager;
use \Phalcon\Mvc\Model\Manager as PhModelsManager;
use \Phalcon\Mvc\Model\Metadata\Files as PhMetadataFiles;
use \Phalcon\Annotations\Adapter\Files as PhAnnotationsAdapter;
use \Fly\AnnotationsInitializer as FlyAnnotationsInitializer;
use \Fly\AnnotationsMetaDataInitializer as FlyAnnotationsMetaDataInitializer;
use \Exceptions\HTTPException as HTTPException;


class Micro extends \Phalcon\Mvc\Micro implements IRun {

    /**
     * Pages that doesn't require authentication
     * @var array
     */
    protected $_noAuthPages;

	/**
	 * Constructor of the App
	 */
	public function __construct() {
        $this->_noAuthPages = array();
	}

	/**
	 * Set Dependency Injector with configuration variables
	 *
	 * @throws Exception		on bad database adapter
	 * @param string $file		full path to configuration file
	 */
	public function setConfig($file) {
		if (!file_exists($file)) {
			throw new \Exception('Unable to load configuration file');
		}

		$di = new \Phalcon\DI\FactoryDefault();
		$di->set('config', new \Phalcon\Config(require $file), true);

		$di->set('db', function() use ($di) {
			$type = strtolower($di->get('config')->database->adapter);
			$creds = array(
				'host' => $di->get('config')->database->host,
				'username' => $di->get('config')->database->username,
				'password' => $di->get('config')->database->password,
				'dbname' => $di->get('config')->database->name
			);

			if ($type == 'mysql') {
				$connection =  new \Phalcon\Db\Adapter\Pdo\Mysql($creds);
			} else if ($type == 'postgres') {
				$connection =  new \Phalcon\Db\Adapter\Pdo\Postgesql($creds);
			} else if ($type == 'sqlite') {
				$connection =  new \Phalcon\Db\Adapter\Pdo\Sqlite($creds);
			} else {
				throw new Exception('Bad Database Adapter');
			}

			return $connection;
		});

		$this->di->set('modelsMetadata', function() use ($di) {
            $metaData = new PhMetadataFiles([
            	'metaDataDir' => $di->get('config')->app_model->metadata
            ]);

            //Set a custom meta-data database introspection
            $metaData->setStrategy(new FlyAnnotationsMetaDataInitializer());

            return $metaData;
        });

		$di->set('modelsManager', function() {
            $eventsManager = new PhEventsManager();
            $modelsManager = new PhModelsManager();
            $modelsManager->setEventsManager($eventsManager);

            //Attach a listener to models-manager
            $eventsManager->attach('modelsManager', new FlyAnnotationsInitializer());

            return $modelsManager;
        });

        $di->set('annotations', function() use ($di) {
            return new PhAnnotationsAdapter([
                'annotationsDir' => $di->get('config')->app_annotations->cache
            ]);
        });

        /**
		 * If our request contains a body, it has to be valid JSON.  This parses the 
		 * body into a standard Object and makes that vailable from the DI.  If this service
		 * is called from a function, and the request body is nto valid JSON or is empty,
		 * the program will throw an Exception.
		 */
		$di->setShared('requestBody', function() {
			$in = file_get_contents('php://input');
			$in = json_decode($in, FALSE);
			// JSON body could not be parsed, throw exception
			if ($in === null) {
				throw new Exceptions\HTTPException(
					'There was a problem understanding the data sent to the server by the application.',
					409,
					array(
						'dev' => 'The JSON body sent to the server was unable to be parsed.',
						'internalCode' => 'REQ1000',
						'more' => ''
					)
				);
			}
			return $in;
		});

		$this->setDI($di);
	}

	/**
	 * Set namespaces to tranverse through in the autoloader
	 *
	 * @link http://docs.phalconphp.com/en/latest/reference/loader.html
	 * @throws Exception
	 * @param string $file		map of namespace to directories
	 */
	public function setAutoload($file, $dir) {
		if (!file_exists($file)) {
			throw new \Exception('Unable to load autoloader file');
		}

		// Set dir to be used inside include file
		$namespaces = include $file;

		$loader = new \Phalcon\Loader();
		$loader->registerNamespaces($namespaces)->register();
	}

	/**
	 * Set Routes\Handlers for the application
	 *
	 * @throws Exception
	 * @param file			file thats array of routes to load
	 */
	public function setRoutes($file) {
		if (!file_exists($file)) {
			throw new \Exception('Unable to load routes file');
		}

		$routes = include($file);

		if (!empty($routes)) {
			foreach($routes as $obj) {

                // Which pages are allowed to skip authentication
                if (isset($obj['authentication']) && $obj['authentication'] === false) {

                    $method = strtolower($obj['method']);

                    if (! isset($this->_noAuthPages[$method])) {
                        $this->_noAuthPages[$method] = array();
                    }

                    $this->_noAuthPages[$method][] = $obj['route'];
                }

				switch($obj['method']) {
					case 'get':
						$this->get($obj['route'], $obj['handler']);
						break;
					case 'post':
						$this->post($obj['route'], $obj['handler']);
						break;
					case 'delete':
						$this->delete($obj['route'], $obj['handler']);
						break;
					case 'put':
						$this->put($obj['route'], $obj['handler']);
						break;
					case 'head':
						$this->head($obj['route'], $obj['handler']);
						break;
					case 'options':
						$this->options($obj['route'], $obj['handler']);
						break;
					case 'patch':
						$this->patch($obj['route'], $obj['handler']);
						break;
					default:
						break;
				}
			}
		}
	}

	/**
	 * Set events to be triggered before/after certain stages in Micro App
	 *
	 * @param object $event		events to add
	 */
	public function setEvents(\Phalcon\Events\Manager $events) {
		$this->setEventsManager($events);
	}

    /**
     *
     */
    public function getUnauthenticated() {
        return $this->_noAuthPages;
    }
	/**
	 * Main run block that executes the micro application
	 *
	 */
	public function run() {
		$app = $this;

		/**
		 * After a route is run, usually when its Controller returns a final value,
		 * the application runs the following function which actually sends the response to the client.
		 *
		 * The default behavior is to send the Controller's returned value to the client as JSON.
		 * However, by parsing the request querystring's 'type' paramter, it is easy to install
		 * different response type handlers.  Below is an alternate csv handler.
		 */
		$app->after(function() use ($app) {
			// OPTIONS have no body, send the headers, exit
			if ($app->request->getMethod() == 'OPTIONS') {
				$app->response->setStatusCode('200', 'OK');
				$app->response->send();
				return;
			}

			// Respond by default as JSON
			if (!$app->request->get('type') || $app->request->get('type') == 'json') {
				// Results returned from the route's controller.  All Controllers should return an array
				$records = $app->getReturnedValue();
				$response = new \Http\Responses\JSONResponse();
				$response->useEnvelope(true) //this is default behavior
					->convertSnakeCase(true) //this is also default behavior
					->send($records);
				return;
			} else if ($app->request->get('type') == 'csv') {
				$records = $app->getReturnedValue();
				$response = new \Http\Responses\CSVResponse();
				$response->useHeaderRow(true)->send($records);
				return;
			} else {
				throw new HTTPException(
					'Could not return results in specified format',
					403,
					array(
						'dev' => 'Could not understand type specified by type paramter in query string.',
						'internalCode' => 'NF1000',
						'more' => 'Type may not be implemented. Choose either "csv" or "json"'
					)
				);
			}
		});

		/**
		 * The notFound service is the default handler function that runs when no route was matched.
		 * We set a 404 here unless there's a suppress error codes.
		 */
		$app->notFound(function () use ($app) {
			throw new HTTPException(
				'Not Found.',
				404,
				array(
					'dev' => 'That route was not found on the server.',
					'internalCode' => 'NF1000',
					'more' => 'Check route for mispellings.'
				)
			);
		});

		/**
		 * If the application throws an HTTPException, send it on to the client as json.
		 * Elsewise, just log it.
		 * TODO:  Improve this.
		 */
		set_exception_handler(function($exception) use ($app) {
			//HTTPException's send method provides the correct response headers and body
			if(is_a($exception, 'HTTPException')){
				$exception->send();
			}
			error_log($exception);
			error_log($exception->getTraceAsString());
		});

		$app->handle();

	}

}
