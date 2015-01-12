<?php namespace Prontotype\View\Twig;

use Prontotype\Filesystem\Finder;

class StringLoader extends \Twig_Loader_String
{

    public function getSource($name)
    {
        return $name;
    }

}
