<?php
namespace Module;

use Phalcon\DI\FactoryDefault as DI;
use Phalcon\Mvc\Dispatcher as PhDispatcher;
use Phalcon\Events\Manager as PhEveManager;
use Fly\Translate\Adapter\Native as FlyTranslate;

class Admin
{
    public function registerAutoloaders()
    {
        $loader = DI::getDefault()->get('loader');

        $loader->registerNamespaces([
            'Controller\Admin' => ROOT_PATH . '/modules/admin/controllers/',
        ],true);
    }

    /**
     * Register the services here to make them general or register in the
     * ModuleDefinition to make them module-specific
     */
    public function registerServices($di)
    {
        //Registering a dispatcher
        $di['dispatcher'] = function () {

            $dispatcher = new PhDispatcher();

            //Attach a event listener to the dispatcher
            $eventManager = new PhEveManager();

            //Notfound redirect
            // $eventManager->attach('dispatch:beforeException', function($event, $dispatcher, $exception) {
            //     //Alternative way, controller or action doesn't exist
            //     if ($event->getType() == 'beforeException') {
            //         switch ($exception->getCode()) {
            //             case PhDispatcher::EXCEPTION_HANDLER_NOT_FOUND:
            //             case PhDispatcher::EXCEPTION_ACTION_NOT_FOUND:
            //                 $dispatcher->forward([
            //                     'module' => 'admin',
            //                     'controller' => 'notfound'
            //                 ]);
            //                 return false;
            //         }
            //     }
            // });

            //attach get param after controller as key/value
            $eventManager->attach("dispatch:beforeDispatchLoop", function($event, $dispatcher) {
                $keyParams = [];
                $params = $dispatcher->getParams();

                //Use odd parameters as keys and even as values
                foreach ($params as $number => $value) {
                    if ($number & 1) {
                        $keyParams[$params[$number - 1]] = $value;
                    }
                }

                //Override parameters
                $dispatcher->setParams($keyParams);
            });

            // Authorization
            // $eventManager->attach('dispatch', new \Fly\Authorization('admin'));

            $dispatcher->setEventsManager($eventManager);
            $dispatcher->setDefaultNamespace('Controller\Admin');

            return $dispatcher;
        };

        $di['view']->setViewsDir(ROOT_PATH . '/modules/admin/views/');
    }
}
