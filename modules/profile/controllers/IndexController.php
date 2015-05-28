<?php

namespace Controller\Profile;

use Fly\BaseController as FlyController;

class IndexController extends FlyController
{
    protected $recordPerPage = 10;

    public function initialize()
    {
        parent::initialize();
    }

    public function indexAction()
    {
        $this->tag->prependTitle('Nguyễn Đức Duy ');
        $this->view->setVars([

        ]);
    }
}
