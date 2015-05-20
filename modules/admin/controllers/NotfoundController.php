<?php
/**
 * \Controller\Admin\NotfoundController.php
 * NotfoundController.php
 *
 * Notfound Controller for back-end area
 *
 * @author      Nguyen Duy <nguyenducduy.it@gmail.com>
 * @since       2015-02-27
 * @category    Fly
 *
 */

namespace Controller\Admin;

use Fly\BaseController as FlyController;

class NotfoundController extends FlyController
{
    public function indexAction()
    {
        $this->tag->prependTitle('Notfound');
    }
}