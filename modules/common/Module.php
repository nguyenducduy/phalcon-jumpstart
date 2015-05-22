<?php
namespace Module;

use Phalcon\DI\FactoryDefault as DI;
use Phalcon\Events\Manager as PhManager;
use Phalcon\Mvc\Dispatcher as PhDispatcher;

class Common
{
    public function registerAutoloaders()
    {
        $loader = DI::getDefault()->get('loader');

        $loader->registerNamespaces([
            'Controller\Common' => ROOT_PATH . '/modules/common/controllers/',
        ], true);
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
            $eventManager = new PhManager();

            //Notfound redirect
            // $eventManager->attach('dispatch:beforeException', function($event, $dispatcher, $exception) {
            //     //Alternative way, controller or action doesn't exist
            //     if ($event->getType() == 'beforeException') {
            //         switch ($exception->getCode()) {
            //             case PhDispatcher::EXCEPTION_HANDLER_NOT_FOUND:
            //             case PhDispatcher::EXCEPTION_ACTION_NOT_FOUND:
            //                 $dispatcher->forward([
            //                     'module' => 'common',
            //                     'controller' => 'notfound'
            //                 ]);
            //                 return false;
            //         }
            //     }
            // });

            // $eventManager->attach('dispatch', new \Fly\Authorization('common'));

            $dispatcher->setEventsManager($eventManager);
            $dispatcher->setDefaultNamespace('Controller\Common');

            return $dispatcher;
        };

        // Load template directory
        $defaultTemplate = $di['config']->defaultTemplate;
        $di['view']->setViewsDir(ROOT_PATH . '/modules/common/views/' . $defaultTemplate . '/');
    }
}
