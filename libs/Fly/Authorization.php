<?php
/**
 * \Fly\Authorization
 * Authorization.php
 *
 * Access List Control class
 *
 * @author      Nguyen Duy <nguyenducduy.it@gmail.com>
 * @since       2014-12-19
 * @category    Fly
 *
 */

namespace Fly;

use Phalcon\DI\FactoryDefault as Di;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;

class Authorization extends \Phalcon\Mvc\User\Component
{
    protected $_module;

    public function __construct($module)
    {
        $this->_module = $module;
    }

    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        $me = null;

        $current_resource = $this->_module . '-' . $dispatcher->getControllerName();
        $current_action = $dispatcher->getActionName();

         // check exsited cookie
        if ($this->cookie->has('remember-me')) {
            $me = unserialize($this->cookie->get('remember-me')->getValue());
            // Sau nay se query database de xac thuc lai xem user nay con ton tai hay da bi banned
            $this->session->set('me', $me);
        }

        // get role_name to authorize from session named "me"
        if ($this->session->has('me')) {
            /**
             * Get ug_name of user from auth session
             */
            $me = $this->session->get('me');
            $role_name = $me->role_name;
        } else {
            /**
             * Default System Role is "Guest"
             */
            $role_name = 'Gue';
            $userId = (int) 0;
        }

        $di = Di::getDefault();

        try {
            //By default the action is deny access
            $this->acl->setDefaultAction(\Phalcon\Acl::DENY);

            /**
             * If apc is enable and acl key existed, get from apc, else query database to check access
             */
            $allowed = $this->acl->isAllowed($role_name, $current_resource, $current_action);

            // khong co quyen + chua dang nhap
            if ($allowed !== \Phalcon\Acl::ALLOW && $me == null) {
                return $this->dispatcher->forward([
                    'module' => 'admin',
                    'controller' => 'login',
                    'action' => 'index'
                ]);
            } elseif ($allowed != \Phalcon\Acl::ALLOW && $me->id > 0) {
                // khong co quyen + dang nhap roi
                return $this->dispatcher->forward([
                    'module' => 'admin',
                    'controller' => 'notfound',
                    'action' => 'index',
                ]);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
