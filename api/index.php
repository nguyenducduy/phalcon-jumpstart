<?php

use Phalcon\DI\FactoryDefault as DefaultDI,
	Phalcon\Mvc\Micro\Collection,
	Phalcon\Config\Adapter\Ini as IniConfig,
	Phalcon\Loader,
	Phalcon\Events\Manager as PhEventsManager,
	Phalcon\Mvc\Model\Manager as PhModelsManager,
	Phalcon\Mvc\Model\Metadata\Files as PhMetadataFiles,
	Phalcon\Annotations\Adapter\Files as PhAnnotationsAdapter,
	Fly\AnnotationsInitializer as FlyAnnotationsInitializer,
	Fly\AnnotationsMetaDataInitializer as FlyAnnotationsMetaDataInitializer;

error_reporting(E_ERROR | E_WARNING | E_PARSE);

// Setup configuration files
define('ROOT_PATH', realpath('.')); // not have end slash

// Necessary paths to autoload & config settings
$configPath = ROOT_PATH . '/config/';
$config = include($configPath . 'config.php');
$autoLoad = include($configPath . 'autoload.php');

/**
 * By default, namespaces are assumed to be the same as the path.
 * This function allows us to assign namespaces to alternative folders.
 * It also puts the classes into the PSR-0 autoLoader.
 */
$loader = new Loader();
$loader->registerNamespaces($autoLoad)->register();

/**
 * The DI is our direct injector.  It will store pointers to all of our services
 * and we will insert it into all of our controllers.
 * @var DefaultDI
 */
$di = new DefaultDI();


/**
 * Return array of the Collections, which define a group of routes, from
 * routes/collections.  These will be mounted into the app itself later.
 */
$di->set('collections', function(){
	return include('./routes/routeLoader.php');
});

/**
 * $di's setShared method provides a singleton instance.
 * If the second parameter is a function, then the service is lazy-loaded
 * on its first instantiation.
 */
$di->set('config', new \Phalcon\Config($config), true);

// As soon as we request the session service, it will be started.
$di->setShared('session', function(){
	$session = new \Phalcon\Session\Adapter\Files();
	$session->start();
	return $session;
});

$di->set('modelsCache', function() {

	//Cache data for one day by default
	$frontCache = new \Phalcon\Cache\Frontend\Data(array(
		'lifetime' => 3600
	));

	//File cache settings
	$cache = new \Phalcon\Cache\Backend\File($frontCache, array(
		'cacheDir' => __DIR__ . '/cache/'
	));

	return $cache;
});

$di->set('modelsMetadata', function() use ($di) {
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
 * Database setup.  Here, we'll use a simple SQLite database of Disney Princesses.
 */
$di->set('db', function() use ($di) {
	$type = strtolower($di->get('config')->database->adapter);
	$creds = array(
		'host' => $di->get('config')->database->host,
		'username' => $di->get('config')->database->username,
		'password' => $di->get('config')->database->password,
		'dbname' => $di->get('config')->database->name,
		"charset"  => 'utf8'
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

/**
 * If our request contains a body, it has to be valid JSON.  This parses the 
 * body into a standard Object and makes that vailable from the DI.  If this service
 * is called from a function, and the request body is nto valid JSON or is empty,
 * the program will throw an Exception.
 */
$di->setShared('requestBody', function() {
	$in = array();
	$in = array_merge($in, $_GET);
    $in = array_merge($in, $_POST);
    $input = file_get_contents("php://input");
    $postVars = json_decode($input, true);
    if (is_array($postVars)) {
        $in = array_merge($in, $postVars);
    }

	// $in = file_get_contents('php://input');
	// $in = json_decode($in, TRUE);

	// JSON body could not be parsed, throw exception
	if($in === null){
		throw new \Exceptions\HTTPException(
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

/**
 * Out application is a Micro application, so we mush explicitly define all the routes.
 * For APIs, this is ideal.  This is as opposed to the more robust MVC Application
 * @var $app
 */
$app = new Phalcon\Mvc\Micro();
$app->setDI($di);



// Setup HMAC Authentication callback to validate user before routing message
// Failure to validate will stop the process before going to proper Restful Route
//$app->setEventsManager(new \Events\Api\HmacAuthenticate($message, $privateKey));

/**
 * Before every request, make sure user is authenticated.
 * Returning true in this function resumes normal routing.
 * Returning false stops any route from executing.
 */

/*
This will require changes to fit your application structure.
It supports Basic Auth, Session auth, and Exempted routes.

It also allows all Options requests, as those tend to not come with
cookies or basic auth credentials and Preflight is not implemented the
same in every browser.
*/


/********************** Security with HMAC ********************
 * Setup HMAC Authentication callback to validate user before routing message
 * Failure to validate will stop the process before going to proper Restful Route
 */

$clientId = $app->request->getHeader('API_ID');
$time = $app->request->getHeader('API_TIME');
$hash = $app->request->getHeader('API_HASH');

$clientConnect = \Model\Api::findFirst([
	'client_id = :clientId: AND status = :status:',
	'bind' => [
		'clientId' => $clientId,
		'status' => 'ACTIVE'
	]
]);

if ($clientConnect) {
	$privateKey = $clientConnect->private_key;
} else {
	$privateKey = '';
}

$data = ${"_" . $_SERVER['REQUEST_METHOD']};
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	unset($data['_url']);
} else {
	$data = (array) $app->requestBody;
}

$message = new \Micro\Messages\Auth($clientId, $time, $hash, $data);

$app->setEventsManager(new \Events\Api\HmacAuthenticate($message, $privateKey));

/******************** End using HMAC security ********************/


/**
 * Mount all of the collections, which makes the routes active.
 */
foreach($di->get('collections') as $collection){
	$app->mount($collection);
}

$di->setShared('lang', function() {
   return '';
});

/**
 * The base route return the list of defined routes for the application.
 * This is not strictly REST compliant, but it helps to base API documentation off of.
 * By calling this, you can quickly see a list of all routes and their methods.
 */
$app->get('/', function() use ($app){
	$routes = $app->getRouter()->getRoutes();
	$routeDefinitions = array('GET'=>array(), 'POST'=>array(), 'PUT'=>array(), 'PATCH'=>array(), 'DELETE'=>array(), 'HEAD'=>array(), 'OPTIONS'=>array());
	foreach($routes as $route){
		$method = $route->getHttpMethods();
		$routeDefinitions[$method][] = $route->getPattern();
	}
	return $routeDefinitions;
});

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
	if($app->request->getMethod() == 'OPTIONS'){
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
			->convertSnakeCase(false) //this is also default behavior
			->send($records);
		return;
	} else if ($app->request->get('type') == 'csv') {
		$records = $app->getReturnedValue();
		$response = new \Http\Responses\CSVResponse();
		$response->useHeaderRow(true)->send($records);
		return;
	} else {
		throw new Exceptions\HTTPException(
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
	throw new Exceptions\HTTPException(
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
set_exception_handler(function($exception) use ($app){
	//HTTPException's send method provides the correct response headers and body
	if(is_a($exception, '\\Exceptions\\HTTPException')){
		$exception->send();
	}
	error_log($exception);
	error_log($exception->getTraceAsString());
});

$app->handle();

