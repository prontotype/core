<?php namespace Prontotype\Providers;

use Prontotype\Container;

class TestProvider implements ProviderInterface
{
    public function register(Container $container)
    {
        $config = $container->make('prontotype.config');
                echo '<pre>';
                print_r($config->get());
                echo '</pre>';
    }
}
