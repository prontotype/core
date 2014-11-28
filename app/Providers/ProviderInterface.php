<?php namespace Prontotype\Providers;

use Auryn\Provider as Container;

interface ProviderInterface
{
    public function register(Container $container);
}