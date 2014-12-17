<?php namespace Prontotype\Providers;

use Auryn\Provider as Container;
use Amu\SuperSharp\Router;
use Amu\SuperSharp\Http\Response;

class HttpProvider implements ProviderInterface
{
    protected $catchallController = 'Prontotype\Http\Controllers\DefaultController::catchall';

    protected $idRedirectController = 'Prontotype\Http\Controllers\DefaultController::redirectById';

    public function register(Container $container)
    {
        $conf = $container->make('prontotype.config');

        $container->define('Prontotype\Http\ControllerHandler', [':container' => $container]);
        $container->define('Amu\SuperSharp\Router', ['handler' => 'Prontotype\Http\ControllerHandler']);
        $container->alias('prontotype.http', 'Prontotype\Http\Application')->share('prontotype.http');
        
        $handler = $container->make('prontotype.http');

        // bind routes --------
        
        $this->buildUserRoutes($handler, $conf->get('routes') ?: array());

        $handler->get('/id:{templateId}', $this->idRedirectController)
                ->name('redirect');

        $handler->get('/{templatePath}', $this->catchallController)
                ->name('default')
                ->value('templatePath', '/')
                ->assert('templatePath', '.+');
 
        // handle errors --------
        
        $viewLoader = $container->make('prontotype.view.loader');

        $handler->notFound(function() use ($viewLoader) {
            // try {
            //     $template = $viewLoader->findNotFoundTemplate();
            //     $response = $template->render();
            // } catch( \Exception $e ) {
            //     $response = 'Page not found';
            // }
            $response = 'Page not found';
            return new Response($response, 404);
        });
        
        $handler->error(function($e) use ($viewLoader) {
            // try { 
            //     $template = $viewLoader->findErrorTemplate();
            //     $response = $template->render();
            // } catch( \Exception $e ) {
            //     $response = 'A server error occurred (' . $e->getMessage() . ')';
            // }
            $response = 'A server error occurred (' . $e->getMessage() . ')';
            return new Response($response, 500);
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