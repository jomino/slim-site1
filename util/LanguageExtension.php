<?php

/*
 *
 * @dev jomino2017
 * 
 */

namespace Util;

class LanguageExtension extends \Twig_Extension
{

    /**
     * @var String
     */
    private $language;

    public function __construct($language)
    {
        $this->language = $language;
    }

    public function getName()
    {
        return 'slim_language';
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('lang', array($this, 'getLang'))
        ];
    }

    public function getLang()
    {
        return $this->language;
    }

}