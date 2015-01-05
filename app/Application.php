<?php namespace Prontotype;

use Auryn\ReflectionPool;
use Auryn\Provider as Injector;
use Prontotype\Config;
use Prontotype\Providers\ProviderInterface;
use Prontotype\Providers\HttpProvider;
use Prontotype\Providers\ViewProvider;
use Prontotype\Providers\ConfigProvider;
use Prontotype\Providers\ConsoleProvider;
use Prontotype\Providers\PluginProvider;

class Application {

    protected $container;

    protected $basePath;

    protected $srcPath;

    protected $providers = [];

    public function __construct($basePath)
    {
        $this->container = new Injector(new ReflectionPool());
        $this->basePath = realpath($basePath);
        $this->srcPath = realpath(__DIR__ . '/..');
        $this->registerServices();
    }

    protected function registerServices()
    {
        $this->register(new ConfigProvider($this->basePath, $this->srcPath));
        $this->register(new ViewProvider());
        $this->register(new HttpProvider());
        $this->register(new ConsoleProvider());
        $this->register(new PluginProvider());
    }

    public function run()
    {
        // register all services
        foreach($this->providers as $provider) {
            $provider->register($this->container);
        }
        // run the appropriate handler
        try {
            $handler = $this->isRunningInConsole() ? $this->container->make('prontotype.console') : $this->container->make('prontotype.http');
            return $handler->run();
        } catch (\Exception $e) {
            echo 'An appilcation error has occured.';
        }
    }

    public function register(ProviderInterface $provider)
    {
        $this->providers[] = $provider;
    }

    public function isRunningInConsole()
    {
        return php_sapi_name() == 'cli';
    }

}