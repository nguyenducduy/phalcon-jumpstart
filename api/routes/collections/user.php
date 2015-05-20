<?php

return call_user_func(function(){

	$userCollection = new \Phalcon\Mvc\Micro\Collection();

	$userCollection
		// VERSION NUMBER SHOULD BE FIRST URL PARAMETER, ALWAYS
		->setPrefix('/v1/user')
		// Must be a string in order to support lazy loading
		->setHandler('\Controllers\V1\UserController')
		->setLazy(true);

	// Set Access-Control-Allow headers.
	$userCollection->options('/', 'optionsBase');
	$userCollection->options('/{id}', 'optionsOne');

	$userCollection->get('/', 'getAction');
	// This is exactly the same execution as GET, but the Response has no body.
	$userCollection->head('/', 'getAction');

	$userCollection->get('/{id:[0-9]+}', 'getoneAction');
	$userCollection->head('/{id:[0-9]+}', 'getoneAction');
	$userCollection->post('/', 'postAction');


	return $userCollection;
});