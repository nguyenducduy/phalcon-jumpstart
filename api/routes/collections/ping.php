<?php

return call_user_func(function(){

	$collection = new \Phalcon\Mvc\Micro\Collection();

	$collection
		// VERSION NUMBER SHOULD BE FIRST URL PARAMETER, ALWAYS
		->setPrefix('/v1/ping')
		// Must be a string in order to support lazy loading
		->setHandler('\Controllers\V1\PingController')
		->setLazy(true);

	// Set Access-Control-Allow headers.
	$collection->options('/', 'optionsBase');

	// First paramter is the route, which with the collection prefix here would be GET /example/
	// Second paramter is the function name of the Controller.
	$collection->get('/', 'indexAction');
	// This is exactly the same execution as GET, but the Response has no body.
	$collection->head('/', 'indexAction');

	$collection->post('/', 'indexAction');

	return $collection;
});