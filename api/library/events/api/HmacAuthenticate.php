<?php

/**
 * Event that Authenticates the client message with HMac
 *
 * @package Events
 * @subpackage Api
 * @author Jete O'Keeffe
 * @version 1.0
 */

namespace Events\Api;

use Interfaces\IEvent as IEvent;
use Http\Responses\JSONResponse as JSONResponse;

class HmacAuthenticate extends \Phalcon\Events\Manager implements IEvent {

	/**
	 * Hmac Message
	 * @var object
	 */
	protected $_msg;

	/**
	 * Private key for HMAC
	 * @var string
	 */
	protected $_privateKey;

	/**
	 * Constructor
	 *
	 * @param object
	 * @param string
	 */
	public function __construct($message, $privateKey) {
		$this->_msg = $message;
		$this->_privateKey = $privateKey;

		// Add Event to validate message
		$this->handleEvent();
	}

	/**
	 * Setup an Event
	 *
	 * Phalcon event to make sure client sends a valid message
	 * @return FALSE|void
	 */
	public function handleEvent() {

		$this->attach('micro', function ($event, $app) {
			if ($event->getType() == 'beforeExecuteRoute') {
				// Need to refactor this
				$data = $this->_msg->getTime() . $this->_msg->getId() . implode($this->_msg->getData());
				$serverHash = hash_hmac('sha256', $data, $this->_privateKey);
				$clientHash = $this->_msg->getHash();
				$allowed = $clientHash === $serverHash ? true : false;

				$method = strtolower($app->router->getMatchedRoute()->getHttpMethods());
				// $unAuthenticated = $app->getUnauthenticated();
				$unAuthenticated = array();

				if (isset($unAuthenticated[$method])) {
					$unAuthenticated = array_flip($unAuthenticated[$method]);

					if (isset($unAuthenticated[$app->router->getMatchedRoute()->getPattern()])) {
						$allowed = true;
					}
				}

				if (!$allowed) {
					// Results returned from the route's controller.  All Controllers should return an array
					$output = array('Access denied');
					$response = new JSONResponse();
					$response->useEnvelope(true) //this is default behavior
						->convertSnakeCase(true) //this is also default behavior
						->setStatusCode(401)
						->send($output, true);

					return false;
				}
			}
			return true;
		});
	}
}
