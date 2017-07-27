<?php

namespace Adminaut\Options;

use Adminaut\Validator;
use Adminaut\Exception;

use Zend\Stdlib\AbstractOptions;
use Zend\Filter;

/**
 * Class FileManagerOptions
 * @package Application\Options
 */
class FileManagerOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $fileManagerFolder = '';

    /**
     * @var string
     */
    protected $cacheFolder = '';

    /**
     * @var int
     */
    protected $chmod = 0755;

    /**
     * @var bool
     */
    protected $defaultIsActive = true;

    /**
     * @var \Application\Validator\Chmod
     */
    protected static $chmodValidator = null;

    /**
     * @return string
     */
    public function getFileManagerFolder()
    {
        return $this->fileManagerFolder;
    }

    /**
     * @param $fileManagerFolder
     * @return \Application\Options\FileManagerOptions
     */
    public function setFileManagerFolder($fileManagerFolder)
    {
        $this->fileManagerFolder = $fileManagerFolder;
        return $this;
    }

    /**
     * @return string
     */
    public function getCacheFolder()
    {
        return $this->cacheFolder;
    }

    /**
     * @param string $cacheFolder
     */
    public function setCacheFolder($cacheFolder)
    {
        $this->cacheFolder = $cacheFolder;
    }

    /**
     * @return int
     */
    public function getChmod()
    {
        return $this->chmod;
    }

    /**
     * @param $chmod
     * @return \Application\Options\FileManagerOptions
     */
    public function setChmod($chmod)
    {
        if (null === static::$chmodValidator) {
            static::$chmodValidator = new Validator\Chmod();
        }
        if (!static::$chmodValidator->isValid($chmod)) {
            throw new Exception\InvalidArgumentException(
                implode(' ; ', static::$chmodValidator->getMessages())
            );
        }
        $this->chmod = $chmod;
        return $this;
    }

    /**
     * @return bool
     */
    public function getDefaultIsActive()
    {
        return $this->defaultIsActive;
    }

    /**
     * @param $defaultIsActive
     * @return \Application\Options\FileManagerOptions
     */
    public function setDefaultIsActive($defaultIsActive)
    {
        $filter = new Filter\Boolean(Filter\Boolean::TYPE_ZERO_STRING);
        $this->defaultIsActive = $filter->filter($defaultIsActive);
        return $this;
    }
}