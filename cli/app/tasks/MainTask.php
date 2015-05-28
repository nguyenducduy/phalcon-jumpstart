<?php

use Phalcon\CLI\Task as PhTask;

class MainTask extends PhTask
{

    public function mainAction()
    {

    }

    public function testAction()
    {
        echo CURRENT_TASK . PHP_EOL;
        echo CURRENT_ACTION . PHP_EOL;
    }
}
