<?php namespace Prontotype\Plugins\Tapestry\Repositories;

use Prontotype\Config;
use Prontotype\View\Twig\Environment;

abstract class AbstractRepository {

    public function __construct(Config $config, Environment $twig)
    {
        $this->config = $config;
        $this->twig = $twig;
    }
    
}