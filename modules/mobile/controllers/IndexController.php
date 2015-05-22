<?php
/**
 * \Controller\Mobile\IndexController.php
 * IndexController.php
 *
 * Index Controller for front-end area
 *
 * @author      Nguyen Duy <nguyenducduy.it@gmail.com>
 * @since       2014-12-19
 * @category    Fly
 *
 */

namespace Controller\Mobile;

use Fly\BaseController as FlyController;

class IndexController extends FlyController
{
    public function initialize()
    {
        parent::initialize();
    }

    public function indexAction()
    {
        echo 'hello mobile site.';
    }

    public function testAction()
    {
        echo 'IndexController - TestAction';
        die;
    }
}
