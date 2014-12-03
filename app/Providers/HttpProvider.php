<?php namespace Prontotype\Providers;

use Auryn\Provider as Container;
use Amu\SuperSharp\Router;
use Amu\SuperSharp\Http\Response;

class HttpProvider implements ProviderInterface
{
    protected $catchallController = 'Prontotype\Http\Controllers\DefaultController::catchall';

    public function register(Container $container)
    {
        $conf = $container->make('prontotype.config');

        $container->define('Prontotype\Http\ControllerHandler', [':container' => $container]);
        $container->define('Amu\SuperSharp\Router', ['handler' => 'Prontotype\Http\ControllerHandler']);
        $container->alias('prontotype.http', 'Prontotype\Http\Application')->share('prontotype.http');
        
        $handler = $container->make('prontotype.http');
        
        $this->buildUserRoutes($handler, $conf->get('routes') ?: array());

        $handler->get('/{templatePath}', $this->catchallController)
                ->name('default')
                ->value('templatePath', '/')
                ->assert('templatePath', '.+');

        $handler->notFound(function(){
            return new Response('Page not found', 404);
        });
        
        $handler->error(function($e){
            return new Response('A server error occurred (' . $e->getMessage() . ')', 500);
        });
    }

    public function buildUserRoutes($handler, $routes)
    {
        $translatedRoutes = array();
        foreach($routes as $routeName => $route) {
            $segments = explode('/', trim($route['match'],'/'));
            $cleanSegments = array();
            $params = array();
            foreach($segments as $segment) {
                if ( strpos($segment, '{') === false ) {
                    $cleanSegments[] = $segment;
                } else {
                    @list($name, $assert, $default) = explode(':', str_replace(array('{','}'), '', $segment));
                    $cleanSegments[] = '{' . $name . '}';
                    $params[$name] = array(
                        'name' => $name,
                        'assert' => $assert,
                        'default' => $default
                    );
                }
            }
            $routePath = '/' . implode('/', $cleanSegments);
            $controller = isset($route['controller']) ? $route['controller'] : $this->catchallController;
            $templatePath = isset($route['template']) ? $route['template'] : null;
            $userRoute = $handler->get($routePath, $controller)->name($routeName)->value('templatePath', $templatePath);
            foreach($params as $paramSet) {
                if ($paramSet['assert']) {
                    $userRoute->assert($paramSet['name'], $paramSet['assert']);
                }
                if ($paramSet['default']) {
                    $userRoute->value($paramSet['name'], $paramSet['default']);
                }
            }
        }
    }
}