<?php
/**
 * \Controller\Common\NotfoundController.php
 * NotfoundController.php
 *
 * Notfound Controller for front-end area
 *
 * @author      Nguyen Duy <nguyenducduy.it@gmail.com>
 * @since       2014-03-13
 * @category    Fly
 *
 */

namespace Controller\Common;

use Fly\BaseController as FlyController;

class NotfoundController extends FlyController
{
    public function initialize()
    {
        parent::initialize();
    }

    public function indexAction()
    {

        $this->tag->prependTitle('Notfound');
        die('Not found');
    }
}