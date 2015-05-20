<?php

namespace Phalcon\Behavior;

use League\Flysystem\Format as Format;
use Phalcon\Logger;
use Phalcon\Mvc\Model\Behavior;
use Phalcon\Mvc\Model\BehaviorInterface;
use Phalcon\Mvc\Model\Exception;
use Phalcon\Mvc\ModelInterface;

class Imageable extends Behavior implements BehaviorInterface
{
    /**
     * Upload image path
     * @var string
     */
    protected $uploadPath = null;

    /**
     * Model field
     * @var null
     */
    protected $imageField = null;

    /**
     * Old model image
     * @var string
     */
    protected $oldFile = null;

    /**
     * Application logger
     * @var \Phalcon\Logger\Adapter\File
     */
    protected $logger = null;

    /**
     * Filesystem Utils
     * @var \Phalcon\League
     */
    protected $filesystem = null;

    /**
     * Option sanitize
     * @var bool
     */
    protected $sanitize = true;

    /**
     * Option isoverwrite
     * @var bool
     */
    protected $isoverwrite = false;

    /**
     * Option customNameField
     * @var string
     */
    protected $customNameField = null;

    /**
     * Option customName
     * @var string
     */
    protected $customName = '';

    /**
     * Allowed types
     * @var array
     */
    protected $allowedFormats = ['image/jpeg', 'image/png', 'image/gif'];

    /**
     * Allowed types
     * @var float
     */
    protected $allowedMaximunSize = null;

    /**
     * Allowed types
     * @var float
     */
    protected $allowedMinimumSize = null;

    public $owner;

    public $options = [];

    /**
    * info
    * @var sesionpath
    */
    public $info = [];

    const ERROR_UPLOAD_OK = 0;
    const ERROR_UPLOAD_UNKNOWN = 1;
    const ERROR_FILESIZE = 2;
    const ERROR_FILETYPE = 4;
    const ERROR_PERMISSION = 8;

    public function __construct($options)
    {

        $di = \Phalcon\DI\FactoryDefault::getDefault();
        $this->logger = $di->get('logger');
        $this->filesystem = $di->get('filemanager');

        $this->setAllowedFormats($options)
             ->setUploadPath($options)
             ->setSanitize($options)
             ->setOverwrite($options)
             ->setAllowMaximumSize($options)
             ->setAllowMinimumSize($options)
             ->setOptions($options);
    }

    public function notify($type, $model)
    {
        

        if (!is_string($type)) {
            throw new Exception('Invalid parameter type.');
        }

        $options = $this->getOptions($type);

        if (is_array($options)) {
            $this->setImageField($options, $model)
                ->setCustomName($options, $model)
                ->processUpload($model);
        }
    }

    public function setOptions($options)
    {

        $this->options = $options;
    }

    public function getOptions($type)
    {   
        if (isset($this->options[$type])) {
            return $this->options[$type];
        }
        return '';
    }

    public function missingMethod($model, $method, $arguments = null)
    {
        if (method_exists($this, $method)) {
            $this->setOwner($model);
            $result = call_user_func_array([$this, $method], $arguments);
            if ($result===null) {
                return '';
            }

            return $result;
        }

        return null;
    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    protected function setImageField(array $options,  ModelInterface $model)
    {
        if (!isset($options['field']) || !is_string($options['field'])) {
            throw new Exception("The option 'field' is required and it must be string.");
        }

        $this->imageField = $options['field'];
        $this->oldFile = $model->{$this->imageField};

        return $this;
    }

    protected function setCustomName(array $options,  ModelInterface $model)
    {
        if (isset($options['customNameField']) && is_string($options['customNameField'])) {
            $this->customNameField = $options['customNameField'];
            if ($this->customNameField != '') {
                $this->customName = $model->{$this->customNameField};
            }
        }

        return $this;
    }

    protected function setAllowedFormats(array $options)
    {
        if (isset($options['allowedFormats']) && is_array($options['allowedFormats'])) {
            $this->allowedFormats = $options['allowedFormats'];
        }

        return $this;
    }

    protected function setAllowMaximumSize(array $options)
    {
        if (isset($options['allowedMaximunSize']) && is_numeric($options['allowedMaximunSize'])) {
            $this->allowedMaximunSize = $options['allowedMaximunSize'];
        }
        return $this;
    }

    protected function setAllowMinimumSize(array $options)
    {
        if (isset($options['allowedMinimumSize']) && is_numeric($options['allowedMinimumSize'])) {
            $this->allowedMinimumSize = $options['allowedMinimumSize'];
        }
        return $this;
    }

    // Symfony\Component\Filesystem\Filesystem uses here, you can do it otherwise
    protected function setUploadPath(array $options)
    {
        if (!isset($options['uploadPath']) || !is_string($options['uploadPath'])) {
            throw new Exception("The option 'uploadPath' is required and it must be string.");
        }

        $path = $options['uploadPath'];

        if (!$this->filesystem->has($path)) {

            $this->filesystem->createDir($path);
        }

        $this->uploadPath = $path;

        return $this;
    }

    protected function setSanitize(array $options)
    {
        if (isset($options['sanitize']) && is_array($options['sanitize'])) {
            $this->sanitize = $options['sanitize'];
        }
        return $this;
    }

    protected function setOverwrite(array $options)
    {   
        if (isset($options['isoverwrite']) && is_array($options['isoverwrite'])) {
            $this->isoverwrite = $options['isoverwrite'];
        }
        return $this;
    }

    protected function processUpload($model = '')
    {
        $error = 0;
        /** @var \Phalcon\Http\Request $request */
        if ($model == '') {
            $owner = $this->getOwner();
        } else {
            $owner = $model;
        }
        $request = $owner->getDI()->getRequest();
        if (true == $request->hasFiles(true)) {
            foreach ($request->getUploadedFiles() as $key => $file) {
                // NOTE!!!
                // Nothing was validated here!
                // Any validations must be are made in a appropriate validator
                $key = $file->getKey();
                $type = $file->getType();
                if (!in_array($type, $this->allowedFormats)) {
                    $error = $error | self::ERROR_FILETYPE;
                }

                if (!$this->checkMaxsize($file, $this->allowedMaximunSize)) {
                    $error = $error | self::ERROR_FILESIZE;
                }

                if (!$this->checkMinsize($file, $this->allowedMinimumSize)) {
                    $error = $error | self::ERROR_FILESIZE;
                }

                if ($error == 0) {

                    $fileName = $file->getName();
                    if ($this->customName != '') {
                        $fileName = $this->customName;
                    }
                    //find namepart and extension part
                    $pos = strrpos($fileName, '.');
                    if ($pos === false) {
                        $pos = strlen($fileName);
                    }

                    $namePart = substr($fileName, 0, $pos);

                    if(isset($this->sanitize) === true
                        && $this->sanitize == true) {
                        $namePart = Format::toLatinVN($namePart, true, true);
                    }

                    if (isset($this->isoverwrite) === true
                        && $this->isoverwrite == false) {
                        while (file_exists(ROOT_PATH . rtrim($this->uploadPath, '/\\')
                            . DIRECTORY_SEPARATOR . $namePart .'.'. $file->getExtension())) {
                            $namePart .= '-' . time();
                        }
                    }

                    if(isset($filename) === false) {
                        $filename   =   $namePart .'.'. $file->getExtension();
                    }

                    $path = rtrim($this->uploadPath, '/\\') . DIRECTORY_SEPARATOR . $filename;

                    $fullPath = ROOT_PATH . $path;

                    if ($file->moveTo($fullPath)) {
                        if ($this->imageField != null) {
                                $owner->writeAttribute($this->imageField, $filename);
                        }
                        $this->setInfo($key, [
                            'path' => $path,
                            'size'  =>  $file->getSize(),
                            'extension'  =>  $file->getExtension()
                        ]);
                        // Delete old file
                        $this->processDelete();
                        $error = self::ERROR_UPLOAD_OK;
                    }
                } else {
                    $error = self::ERROR_UPLOAD_UNKNOWN;
                }
            }
        } else {
            $error = self::ERROR_UPLOAD_UNKNOWN;
        }

        return $error;
    }

    // Symfony\Component\Filesystem\Filesystem uses here, you can do it otherwise
    protected function processDelete()
    {
        if ($this->oldFile) {
            $fullPath = rtrim($this->uploadPath, '/\\') . DIRECTORY_SEPARATOR . $this->oldFile;

            try {
                $this->filesystem->delete($fullPath);
                $this->logger->log(Logger::INFO, sprintf('File %s deleted successful.', $fullPath));
            } catch(\Exception $e) {
                $this->logger->log(Logger::ALERT, sprintf(
                    'An error occurred deleting file %s: %s', $fullPath, $e->getMessage()
                ));
            }
        }
    }

    public function checkMinsize(\Phalcon\Http\Request\File $file, $value)
    {

        $pass = true;
        if ($value !== null && is_numeric($value)) {
            if($file->getSize() < (int)$value) {
                $pass = false;
            }
        }

        return $pass;
    }

    /**
     * Check maximum file size
     *
     * @param \Phalcon\Http\Request\File $file
     * @param mixed $value
     * @return bool
     */
    public function checkMaxsize(\Phalcon\Http\Request\File $file, $value)
    {   
        $pass = true;
        if ($value !== null && is_numeric($value)) {
            if($file->getSize() > (int)$value) {
                $pass = false;
            }
        }

        return $pass;
    }

    /** Get uploaded files info
     *
     * @return \Phalcon\Session\Bag
     */
    public function getInfo() {

        // error container
        return $this->info;

    }
    /** set uploaded files info
     *
     * @return \Phalcon\Session\Bag
     */
    public function setInfo($key, $value) {

        // error container
        $this->info[$key] = $value;

    }

    public function isSuccessUpload()
    {
        return self::ERROR_UPLOAD_OK;
    }

    public function isErrorFileTypeUpload()
    {
        return self::ERROR_FILETYPE;
    }

    public function isErrorFileSizeUpload()
    {
        return self::ERROR_FILESIZE;
    }

    public function isErrorPermissionUpload()
    {
        return self::ERROR_PERMISSION;
    }

    public function isErrorUnknownUpload()
    {
        return self::ERROR_UPLOAD_UNKNOWN;
    }
}