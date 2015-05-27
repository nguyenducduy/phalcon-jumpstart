<?php
/**
 * \Controller\Common\IndexController.php
 * IndexController.php
 *
 * Index Controller for front-end area
 *
 * @author      Nguyen Duy <nguyenducduy.it@gmail.com>
 * @since       2014-12-19
 * @category    Fly
 *
 */

namespace Controller\Common;

use Fly\BaseController as FlyController;

class IndexController extends FlyController
{
    public function initialize()
    {
        parent::initialize();
    }

    public function indexAction()
    {
        if ($this->request->isPost()) {
            $this->flash->success('Open Terminal and run following command to install: <br><code> php cli/cli.php migrate rebuild </code> <img src="'. $this->url->getBaseUri .'rebuild-database-success.png" /><br><br> After run success, go to <code>http://yoursite.com/admin</code> and login with <br> <b>Email</b>: admin@fly.com <br> <b>Password</b>: 1 <br><br> Now, you can fly with phalcon :)');
        }

        $this->tag->prependTitle('Welcome ');
    }
}
