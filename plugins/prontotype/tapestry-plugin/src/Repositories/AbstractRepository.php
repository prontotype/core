<?php namespace Prontotype\Plugins\Tapestry\Repositories;

use Prontotype\Config;

abstract class AbstractRepository {

    public function __construct(Config $config)
    {
        $this->config = $config;
    }
    
}