<?php namespace Prontotype\Providers;

use Auryn\Provider as Container;
use Amu\SuperSharp\Router;
use Amu\SuperSharp\Http\Response;

class HttpProvider implements ProviderInterface
{
    public function register(Container $container)
    {
        $container->define('Prontotype\Http\ControllerHandler', [':container' => $container]);
        $container->define('Amu\SuperSharp\Router', ['handler' => 'Prontotype\Http\ControllerHandler']);
        $container->alias('prontotype.http', 'Prontotype\Http\Application')->share('prontotype.http');
        
        $handler = $container->make('prontotype.http');
        
        $handler->get('/{urlPath}', 'Prontotype\Http\Controllers\DefaultController:catchall')
            ->value('urlPath', '/')
            ->assert('urlPath', '.+');
        
        $handler->notFound(function(){
            return new Response('Page not found', 404);
        });
        
        $handler->error(function($e){
            return new Response('A server error occurred (' . $e->getMessage() . ')', 500);
        });
    }
}