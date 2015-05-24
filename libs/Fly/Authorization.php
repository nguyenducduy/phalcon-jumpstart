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
use Fly\Helper;

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

        // check exsited cookie
        if ($this->cookie->has('remember-me')) {
            $rememberMe = $this->cookie->get('remember-me');
            $userId = $rememberMe->getValue();

            $myUser = \Model\User::findFirst([
                'id = :id: AND status = :status:',
                'bind' => [
                    'id' => $userId,
                    'status' => \Model\User::STATUS_ENABLE
                ]
            ]);
            if ($myUser) {
                $me =  new \stdClass();
                $me->id = $myUser->id;
                $me->email = $myUser->email;
                $me->name = $myUser->name;
                $me->role = $myUser->role;
                $me->roleName = $myUser->getRoleName();
                $me->avatar = $myUser->avatar;
            }

            $this->session->set('me', $me);
            $role = $myUser->role;
        } else {
            //Get role name from session
            if ($this->session->has('me')) {
                $me = $this->session->get('me');
                $role = $me->role;
            } else {
                $role = ROLE_GUEST;
            }
        }

        $current_resource = $this->_module . '-' . $dispatcher->getControllerName();
        $current_action = $dispatcher->getActionName();

        if (!is_file(ROOT_PATH . '/cache/security/acl.data')) {
            $this->getAcl();

            //Cache acl in file system.
            $this->filemanager->put('cache/security/acl.data', serialize($this->acl));
        } else {
            // Restore acl object from serialized file
            $this->acl = unserialize($this->filemanager->read('cache/security/acl.data'));
        }

        $allowed = $this->acl->isAllowed($role, $current_resource, $current_action);

        if ($allowed !== true && $me == null) {
            // khong co quyen + chua dang nhap
            return $this->dispatcher->forward([
                'module' => $this->_module,
                'controller' => 'login',
                'action' => 'index',
                'params' => ['redirect' => Helper::getCurrentUrl()]
            ]);
        } elseif ($allowed != true && $me->id > 0) {
            // khong co quyen + dang nhap roi
            return $this->dispatcher->forward([
                'module' => $this->_module,
                'controller' => 'notfound',
                'action' => 'index',
            ]);
        }
    }

    private function getAcl()
    {
        $groupList = array_keys($this->permission);
        foreach ($groupList as $groupConst => $groupValue) {
            // Add Role
            $this->acl->addRole(new \Phalcon\Acl\Role($groupValue));

            if (isset($this->permission[$groupValue]) && is_array($this->permission[$groupValue]) == true) {
                foreach ($this->permission[$groupValue] as $group => $controller) {
                    foreach ($controller as $action) {
                        $actionArr = explode(':', $action);
                        $resource = strtolower($group) . '-' . $actionArr[0];

                        // Add Resource
                        $this->acl->addResource($resource, $actionArr[1]);

                        // Grant role to resource
                        $this->acl->allow($groupValue, $resource, $actionArr[1]);
                    }
                }
            }
        }
    }
}
