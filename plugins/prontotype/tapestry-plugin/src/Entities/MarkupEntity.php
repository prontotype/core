<?php namespace Prontotype\Plugins\Tapestry\Entities;

use Prontotype\View\Twig\Environment;

class MarkupEntity extends AbstractEntity {

    public function __construct($file, Environment $twig)
    {
        $this->twig = $twig;
        parent:: __construct($file);
    }

    public function render()
    {
        return trim($this->twig->render($this->file->getRelativePathname()));
    }
    
    public function getNotes()
    {
        return trim($this->file->getMetadataValue('notes'));
    }

}