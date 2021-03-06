<?php

namespace Adminaut\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * Class AppearanceOptions
 * @package Adminaut\Options
 */
class AppearanceOptions extends AbstractOptions
{

    /**
     * @var bool
     */
    protected $__strictMode__ = false;

    /**
     * @var string
     */
    private $skin = 'blue';

    /**
     * @var string|null
     */
    private $skinFile;

    /**
     * @var string
     */
    private $title = 'Adminaut';

    /**
     * @var string
     */
    private $description = 'Adminaut - universal automatic administration system';

    /**
     * @var string
     */
    private $footer = '';

    /**
     * @var array
     */
    private $logo = [
        'type' => 'image',
        'large' => 'adminaut/img/adminaut-logo.svg',
        'small' => 'adminaut/img/adminaut-logo-mini.svg',
    ];

    /**
     * @var string
     */
    private $themeColor = '#3c8dbc';

    /**
     * @return string
     */
    public function getSkin()
    {
        return $this->skin;
    }

    /**
     * @param string $skin
     */
    public function setSkin($skin)
    {
        $this->skin = $skin;
    }

    /**
     * @return null|string
     */
    public function getSkinFile()
    {
        return $this->skinFile;
    }

    /**
     * @param null|string $skinFile
     */
    public function setSkinFile($skinFile)
    {
        $this->skinFile = $skinFile;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getFooter()
    {
        return $this->footer;
    }

    /**
     * @param string $footer
     */
    public function setFooter($footer)
    {
        $this->footer = $footer;
    }

    /**
     * @return array
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param array $logo
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }

    /**
     * @return string
     */
    public function getThemeColor()
    {
        return $this->themeColor;
    }

    /**
     * @param string $themeColor
     */
    public function setThemeColor($themeColor)
    {
        $this->themeColor = $themeColor;
    }
}
