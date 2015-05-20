<?php
/**
 * \Controller\Admin\DashboardController.php
 * DashboardController.php
 *
 * Dashboard Controller for back-end area
 *
 * @author      Nguyen Duy <nguyenducduy.it@gmail.com>
 * @since       2014-12-28
 * @category    Fly
 *
 */

namespace Controller\Admin;

use Fly\BaseController as FlyController;

class DashboardController extends FlyController
{
    public function indexAction()
    {
        $this->tag->prependTitle('Overview');
        $this->breadcrumb->add('Dashboard', 'admin/dashboard');
        $this->breadcrumb->add('Dashboard', 'admin/dashboard');
        $this->view->setVars([
            'breadcrumb' => $this->breadcrumb->generate()
        ]);
    }
}