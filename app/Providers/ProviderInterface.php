<?php namespace Prontotype\Providers;

use Prontotype\Container;

interface ProviderInterface
{
    public function register(Container $container);

    public function boot(Container $container);
}