<?php
/**
 * \Fly\BaseController
 * BaseController.php
 *
 * Core Controller class
 *
 * @author      Nguyen Duy <nguyenducduy.it@gmail.com>
 * @since       2014-12-19
 * @category    Fly
 *
 */

namespace Fly;

use Phalcon\DI\FactoryDefault as DI;
use Phalcon\Mvc\View;
use Fly\Translate\Adapter\Native as FlyTranslate;

class BaseController extends \Phalcon\Mvc\Controller
{
    public $breadcrumb = null;

    /**
     * Initializes the controller
     */
    public function initialize()
    {
        $this->breadcrumb = new Breadcrumbs();
        $this->tag->setTitle(
            ' - ' . $this->config->app_name
        );
    }

    public function beforeExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher)
    {

        //Registering translation
        $this->di->setShared('lang', function() {
            $language = '';

            // Detect language via cookie
            if ($this->cookie->has('language')) {
                $this->cookie->useEncryption(false);
                $language = $this->cookie->get('language')->getValue();
            } else {
                //Get default language
                $language = $this->config->defaultLanguage;
            }

            return new FlyTranslate([
                'module' => strtolower($this->router->getModuleName()),
                'controller' => $this->router->getControllerName(),
                'language' => $language
            ]);
        });

        $this->view->setVar('lang', $this->di->get('lang'));
        $this->view->setVar('redirectUrl', base64_encode(urlencode($this->getCurrentUrl())));
    }

    /**
     * This sets all the view variables before rendering
     */
    public function afterExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher) {
        if ($this->request->isAjax() == true) {
            $this->view->disableLevel([
                View::LEVEL_ACTION_VIEW => true,
                View::LEVEL_LAYOUT => true,
                View::LEVEL_MAIN_LAYOUT => true,
                View::LEVEL_AFTER_TEMPLATE => true,
                View::LEVEL_BEFORE_TEMPLATE => true
            ]);

            $this->response->setContentType('application/json', 'UTF-8');
            $data = $this->view->getParamsToView();

            /*
             * Or for returnish action lovers:
             *   ->  $data = $dispatcher->getReturnedValue();
             */

            /* Set global params if is not set in controller/action */
            if (is_array($data)) {
                $data['success'] = isset($data['success']) ? $data['success'] : true;
                $data['message'] = isset($data['message']) ? $data['message'] : '';
                $data = json_encode($data);
            }

            $this->response->setContent($data);
        }

        return $this->response->send();
    }

    /**
     * Gets the name of the module to create a hash from it (cache)
     *
     * @param string $prefix
     * @param string $key
     *
     * @return string
     */
    protected function getCacheHash($prefix = '', $key = '')
    {
        $name = strtolower($this->getName());

        $hash  = ($prefix) ? $prefix: '';
        $hash .= ($key)    ? $key:    '';
        $hash .= $name;

        return $hash;
    }

    /**
     * Clears the cache - crude but efficient way for the moment
     *
     * @todo Make the cache keys relevant to what we cache
     */
    protected function clearCache()
    {
        $path    = ROOT_PATH . '/cache/';
        $pattern = $path . '*';
        foreach (glob($pattern) as $file) {
            if ($file != ($path . 'dummy.txt')) {
                unlink($file);
            }
        }
    }

    /**
     * get Current URL
     */
    public function getCurrentUrl()
    {
        $url = '';
        $moduleName = $this->dispatcher->getModuleName();
        $controllerName = $this->dispatcher->getControllerName();
        $actionName = $this->dispatcher->getActionName();
        $url = $moduleName . '/' . $controllerName . '/' . $actionName;

        return str_replace('/index', '', $url);
    }
}
