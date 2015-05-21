<?php
/**
 * \Controller\Admin\IndexController.php
 * IndexController.php
 *
 * Index Controller for back-end area
 *
 * @author      Nguyen Duy <nguyenducduy.it@gmail.com>
 * @since       2014-12-19
 * @category    Fly
 *
 */

namespace Controller\Admin;

use Fly\BaseController as FlyController;
use Fly\Helper;

class IndexController extends FlyController
{
    public function indexAction()
    {
        if ($this->session->has('me') == false) {
            return $this->dispatcher->forward([
                'module' => 'admin',
                'controller' => 'login',
                'action' => 'index'
            ]);
        } else {
            $this->dispatcher->forward([
                'module' => 'admin',
                'controller' => 'dashboard',
                'action' => 'index'
            ]);
        }
    }

    public function logoutAction($log = false)
    {
        // delete cookie
        if ($this->cookie->has('remember-me')) {
            $rememberMe = $this->cookie->get('remember-me');
            $rememberMe->delete();
        }

        // remove session
        $this->session->destroy();

        $this->response->redirect('admin/');
    }
}