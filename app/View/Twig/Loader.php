<?php namespace Prontotype\View\Twig;

class Loader extends \Twig_Loader_Filesystem
{
    public function setDefaultExtension($defaultExt)
    {
        $this->defaultExt = ltrim($defaultExt, '.');
    }

    public function findTemplate($name)
    {
        $ext = pathinfo($name, PATHINFO_EXTENSION);
        if ( ! isset($ext) || empty($ext) ) {
            $name = $name . '.' . $this->defaultExt;
        }
        return parent::findTemplate($name);
    }

}

