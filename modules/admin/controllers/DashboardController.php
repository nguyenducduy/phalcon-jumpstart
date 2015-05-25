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
        $serverPHP = $_SERVER['SERVER_SOFTWARE'];
        $pos = strripos($serverPHP, 'php');

        $formData['fserverip'] = $this->request->getServerAddress();
        $formData['fclientip'] = $this->request->getClientAddress();
        $formData['fserver'] = trim(substr($serverPHP, 0, $pos-1));
        $formData['fphp'] = trim(substr($serverPHP, $pos));
        $formData['fuseragent'] = $this->request->getUserAgent();
        $now = new \DateTime();
        $formData['now'] = $now->format('d/m/Y H:i:s');

        $this->tag->prependTitle($this->lang->get('IndexTitle'));
        $this->breadcrumb->add('Dashboard', 'admin/dashboard');
        $this->breadcrumb->add('Dashboard', 'admin/dashboard');
        $this->view->setVars([
            'breadcrumb' => $this->breadcrumb->generate(),
            'formData' => $formData
        ]);
    }
}