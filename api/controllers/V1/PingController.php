<?php
namespace Controllers\V1;

class PingController extends BaseController
{
	public function indexAction()
	{
		return array('Pong');
	}
}