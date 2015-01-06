<?php namespace Prontotype\Providers;

use Prontotype\Container;

class EventProvider implements ProviderInterface
{
    public function register(Container $container)
    {
        $container->share('League\Event\Emitter')->alias('prontotype.events', 'League\Event\Emitter');
    }
}
