<?php
/*
  +------------------------------------------------------------------------+
  | Phalcon Framework                                                      |
  +------------------------------------------------------------------------+
  | Copyright (c) 2011-2012 Phalcon Team (http://www.phalconphp.com)       |
  +------------------------------------------------------------------------+
  | This source file is subject to the New BSD License that is bundled     |
  | with this package in the file docs/LICENSE.txt.                        |
  |                                                                        |
  | If you did not receive a copy of the license and are unable to         |
  | obtain it through the world-wide-web, please send an email             |
  | to license@phalconphp.com so we can send you a copy immediately.       |
  +------------------------------------------------------------------------+
  | Authors: Andres Gutierrez <andres@phalconphp.com>                      |
  |          Eduar Carvajal <eduar@phalconphp.com>                         |
  +------------------------------------------------------------------------+
*/
namespace Fly\Translate\Adapter;

use Phalcon\Translate\Adapter;
use Phalcon\Translate\AdapterInterface;
use Phalcon\Translate\Exception;
use Phalcon\Translate\Adapter\NativeArray as PhNativeArray;

class Native extends Adapter implements AdapterInterface
{
    protected $options;
    protected $languageContent;
    /**
     * Class constructor.
     *
     * @param  array                        $options
     * @throws \Phalcon\Translate\Exception
     */
    public function __construct($options)
    {
        if (!isset($options['module'])) {
        throw new Exception("Parameter 'namespace' is required");
        }

        if (!isset($options['controller'])) {
        throw new Exception("Parameter 'controller' is required");
        }

        if (!isset($options['language'])) {
        throw new Exception("Parameter 'language' is required");
        }

        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     *
     * @param  string $index
     * @param  array  $placeholders
     * @return string
     */
    public function query($index, $placeholders = null)
    {

    }

    public function get($index)
    {
        $languageContent = [];
        $options = $this->options;

        $langPath = ROOT_PATH . '/language/' .
        $options['language'] . '/' .
        $options['module'] . '/' .
        $options['controller'] . '.php';

        // Check if we have a translation file for that lang
        if (file_exists($langPath)) {
            $languageContent = require $langPath;
        }

        $this->languageContent = $languageContent;

        //Return a translation object
        $obj = new PhNativeArray([
            'content' => $this->languageContent
        ]);

        return $obj->_($index);
    }

    /**
     * {@inheritdoc}
     *
     * @param  string  $index
     * @return boolean
     */
    public function exists($index)
    {
        $options = $this->options;
    }
}
