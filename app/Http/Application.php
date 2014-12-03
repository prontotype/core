<?php namespace Prontotype\Http;

use Amu\SuperSharp\Router;

use Prontotype\Http\Request as ProntotypeRequest;
use Prontotype\Exception\NotFoundException;

class Application
{
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function __call($name, $args)
    {
        if (method_exists($this->router, $name)) {
            return call_user_func_array(array($this->router, $name), $args);
        }
    }

    public function notFound($callback)
    {
        $this->onNotFound = $callback;
    }

    public function error($callback)
    {
        $this->onError = $callback;
    }

    public function run()
    {
        try {
            $response = $this->router->match(ProntotypeRequest::createFromGlobals());
        } catch (NotFoundException $e) {
            $response = $this->runError('onNotFound', $e);
        } catch (\Exception $e) {
            $response = $this->runError('onError', $e);
        }
        $response->send();
    }

    protected function runError($errorName, $e)
    {
        if ( is_callable($this->$errorName)) {
            $func = $this->$errorName;
            return $func($e);
        } else {
            throw $e;
        }
    }

}