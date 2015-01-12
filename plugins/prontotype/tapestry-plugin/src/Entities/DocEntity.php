<?php namespace Prontotype\Plugins\Tapestry\Entities;

use Prontotype\View\Twig\Environment;

class DocEntity extends AbstractEntity {

    public function render()
    {
        return $this->file->getBody();
    }

}