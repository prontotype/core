<?php namespace Prontotype\Plugins\Tapestry\Entities;

use Prontotype\View\Twig\Environment;

class MarkupEntity {

    public function __construct($file, Environment $twig)
    {
        $this->file = $file;
        $this->twig = $twig;
    }

    public function render()
    {
        return trim($this->twig->render($this->file->getRelativePathname()));
    }

    public function __call($name, $args)
    {
        if (method_exists($this->file, $name)) {
            return call_user_func_array(array($this->file, $name), $args);
        }
    }

    public function getNotes()
    {
        return trim($this->file->getMetadataValue('notes'));
    }

}