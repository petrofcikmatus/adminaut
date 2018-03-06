<?php

namespace Adminaut\Datatype;

use Zend\InputFilter\FileInput;
use Zend\Validator\File\IsImage;
use Zend\Validator\ValidatorInterface;

/**
 * Class FileImage
 * @package Adminaut\Datatype
 */
class FileImage extends File
{
    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * FileImage constructor.
     * @param int|null|string $name
     * @param array $options
     */
    public function __construct($name, array $options)
    {
        parent::__construct($name, $options);
        $this->setAttribute('accept', 'image/*');
    }

    /**
     * @return IsImage|ValidatorInterface
     */
    public function getValidator()
    {
        if (null === $this->validator) {
            $this->validator = new IsImage();
            $this->validator->disableMagicFile(true);
        }
        return $this->validator;
    }

    /**
     * @return array
     */
    public function getInputSpecification()
    {
        return [
            'type' => FileInput::class,
            'name' => $this->getName(),
            'required' => false,
            'validators' => [
                $this->getValidator(),
            ],
        ];
    }
}
