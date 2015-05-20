<?php
namespace Fly;

use Phalcon\DI\FactoryDefault as DI;

class Breadcrumbs
{
    protected $di;

    /**
     * @var array
     */
    public $_elements = array();
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->di = DI::getDefault();
    }
    /**
     * Adds a new element in the stack
     *
     * @param string $caption
     * @param string $link
     */
    public function add($caption, $link)
    {
        $this->_elements[] = array(
            'active' => false,
            'link'   => $this->di->get('config')->app_baseUri . $link,
            'text'   => $caption,
        );
    }
    /**
     * Resets the internal element array
     */
    public function reset()
    {
        $this->_elements = array();
    }
    /**
     * Generates the JSON string from the internal array
     *
     * @return string
     */
    public function generate()
    {
        $lastKey = key(array_slice($this->_elements, -1, 1, true));
        $this->_elements[$lastKey]['active'] = true;
        return $this->_elements;
    }
}