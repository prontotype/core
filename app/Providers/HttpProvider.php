<?php namespace Prontotype\Providers;

use Prontotype\Container;
use League\Event\Event;
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
        
        $events->addListener('plugins.loaded', function() use ($handler) {
            
            $handler->get('/{templatePath}', 'Prontotype\Http\Controllers\DefaultController::notFound')
                ->name('notfound')
                ->assert('templatePath', '[^:]+:.+');

            $handler->get('/{templatePath}', 'Prontotype\Http\Controllers\DefaultController::catchall')
                ->name('default')
                ->value('templatePath', '/')
                ->assert('templatePath', '.+');
        });
        
        // handle errors --------
        
        // $viewLoader = $container->make('prontotype.view.loader');

        // $handler->notFound(function() use ($viewLoader) {
        //     // try {
        //     //     $template = $viewLoader->findNotFoundTemplate();
        //     //     $response = $template->render();
        //     // } catch( \Exception $e ) {
        //     //     $response = 'Page not found';
        //     // }
        //     $response = 'Page not found';
        //     return new Response($response, 404);
        // });
        
        // $handler->error(function($e) use ($viewLoader) {
        //     // try { 
        //     //     $template = $viewLoader->findErrorTemplate();
        //     //     $response = $template->render();
        //     // } catch( \Exception $e ) {
        //     //     $response = 'A server error occurred (' . $e->getMessage() . ')';
        //     // }
        //     $response = 'A server error occurred (' . $e->getMessage() . ')';
        //     return new Response($response, 500);
        // });

    }

}