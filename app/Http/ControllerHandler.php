<?php namespace Prontotype\Http;

use Amu\SuperSharp\Http\Request;
use Amu\SuperSharp\Handler\HandlerInterface;

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

        $params['_params'] = array();

        // populate template path param if undefined
        if ($params['templatePath'] === null) {
            $params['templatePath'] = $request->getRequestUri();
        }

        // replace placeholders in the template path
        $keys = array();
        $values = array();
        foreach($params as $name => $param) {
            if ( is_string($param) && strpos($name, '_') !== 0 && $name !== 'templatePath' ) {
                $params['_params'][$name] = $param;
                $keys[] = '{' . $name . '}';
                $values[] = $param;
            }
        }

        $params['templatePath'] = str_replace($keys, $values, $params['templatePath']);
        $request->attributes->add($params);

        $controller = $this->convertCallback($params['_controller']);
        $arguments = $this->getArguments($request, $controller);
        $beforeCallable = array($controller[0], 'before');
        call_user_func_array($beforeCallable, [$request]);
        return call_user_func_array($controller, $arguments);
    }

    protected function convertCallback($name)
    {
        list($service, $method) = explode('::', $name, 2);
        return array($this->container->make($service), $method);
    }

    protected function getArguments(Request $request, $controller)
    {
        $r = new \ReflectionMethod($controller[0], $controller[1]);
        return $this->doGetArguments($request, $controller, $r->getParameters());
    }

    protected function doGetArguments(Request $request, $controller, array $parameters)
    {
        $attributes = $request->attributes->all();
        $arguments = array();
        foreach ($parameters as $param) {
            if (array_key_exists($param->name, $attributes)) {
                $arguments[] = $attributes[$param->name];
            } elseif ($param->getClass() && $param->getClass()->isInstance($request)) {
                $arguments[] = $request;
            } elseif ($param->isDefaultValueAvailable()) {
                $arguments[] = $param->getDefaultValue();
            } else {
                $repr = sprintf('%s::%s()', get_class($controller[0]), $controller[1]);
                throw new \RuntimeException(sprintf('Controller "%s" requires that you provide a value for the "$%s" argument (because there is no default value or because there is a non optional argument after this one).', $repr, $param->name));
            }
        }
        return $arguments;
    }

}