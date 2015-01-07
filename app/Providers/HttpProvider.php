<?php namespace Prontotype\Providers;

use Prontotype\Container;
use Prontotype\Event;
use Amu\SuperSharp\Router;
use Amu\SuperSharp\Http\Response;

class HttpProvider implements ProviderInterface
{
    public function register(Container $container)
    {
        $conf = $container->make('prontotype.config');
        $container->define('Prontotype\Http\ControllerHandler', [':container' => $container]);
        $container->define('Amu\SuperSharp\Router', ['handler' => 'Prontotype\Http\ControllerHandler']);
        $container->alias('prontotype.http', 'Prontotype\Http\Application')->share('prontotype.http');
    }

    public function boot(Container $container)
    {
        $events = $container->make('prontotype.events');
        $handler = $container->make('prontotype.http');

        $events->addListener('plugins.loaded', function() use ($handler, $events) {
            $events->emit(Event::named('appRoutes.register.start'));
            $handler->get('/{templatePath}', 'Prontotype\Http\Controllers\DefaultController::catchall')
                ->name('default')
                ->value('templatePath', '/')
                ->assert('templatePath', '[^:]+');
            $events->emit(Event::named('appRoutes.register.end'));
        });

        $handler->notFound(function() {
            $response = 'Page not found';
            return new Response($response, 404);
        });
        
        $handler->appError(function($e) {
            $response = 'An application error occurred.';
            return new Response($response, 500);
        });
    }

}