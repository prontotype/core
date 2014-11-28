<?php namespace Prontotype\Http;

use Amu\SuperSharp\Http\Request;
use Amu\SuperSharp\Handler\HandlerInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;

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

    protected function convertCallback($name)
    {
        list($service, $method) = explode(':', $name, 2);
        return array($this->container->make($service), $method);
    }
}