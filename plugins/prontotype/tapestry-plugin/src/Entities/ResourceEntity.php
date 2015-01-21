<?php namespace Prontotype\Plugins\Tapestry\Entities;

use Prontotype\View\Twig\Environment;

class ResourceEntity extends AbstractEntity {

    public function getTitle()
    {
        return $this->getBasename();
    }

}