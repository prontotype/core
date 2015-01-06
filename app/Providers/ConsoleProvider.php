<?php namespace Prontotype\Providers;

use Prontotype\Container;

class ConsoleProvider implements ProviderInterface
{
    public function register(Container $container)
    {
        $conf = $container->make('prontotype.config');

        $container->define('Prontotype\Console\Commands\ServeCommand', [
            ':basePath' => $conf->get('public.directory'),
            ':serverPath' => $conf->get('prontotype.server')
        ]);
        $container->share('Prontotype\Console\Application')->alias('prontotype.console', 'Prontotype\Console\Application');

        $handler = $container->make('prontotype.console');
        $handler->setAutoExit(false);
    }

    public function boot(Container $container)
    {
        $handler = $container->make('prontotype.console');
        $handler->add($container->make('Prontotype\Console\Commands\ServeCommand'));
    }
}