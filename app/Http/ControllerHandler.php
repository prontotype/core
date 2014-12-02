<?php namespace Prontotype\Http;

use Amu\SuperSharp\Http\Request;
use Amu\SuperSharp\Handler\HandlerInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Prontotype\Http\ControllerMapper;

class ControllerHandler implements HandlerInterface
{
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function handle($params, Request $request)
    {
        if (empty($params['_controller'])) {
            throw new Exception('The ControllerHandler requires a non-empty controller value');
        }
        $resolver = new ControllerResolver();
        $request->attributes->add($params);
        $controller = $this->convertCallback($params['_controller']);
        $arguments = $resolver->getArguments($request, $controller);
        return call_user_func_array($controller, $arguments);
    }

    protected function convertCallback($routeString)
    {
        $mapper = new ControllerMapper();
        $callable = $mapper->getCallback($routeString);
        $callable[0] = $this->container->make($callable[0]);
        return $callable;
    }
}