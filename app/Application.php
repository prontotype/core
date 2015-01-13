<?php namespace Prontotype;

use Auryn\ReflectionPool;
use Prontotype\Container;
use Prontotype\Config;
use Prontotype\Providers\ProviderInterface;
use Prontotype\Providers\HttpProvider;
use Prontotype\Providers\ViewProvider;
use Prontotype\Providers\ConfigProvider;
use Prontotype\Providers\ConsoleProvider;
use Prontotype\Providers\PluginProvider;
use Prontotype\Providers\TestProvider;
use Prontotype\Providers\EventProvider;
use Prontotype\Providers\TapestryProvider;

class Application {

    protected $container;

    protected $basePath;

    protected $srcPath;

    protected $providers = [];

    public function __construct($basePath)
    {
        $this->container = new Container(new ReflectionPool());
        $this->container->share($this->container)->alias('prontotype.container', 'Prontotype\Container');
        $this->basePath = realpath($basePath);
        $this->srcPath = realpath(__DIR__ . '/..');
        $this->registerServices();
    }

    protected function registerServices()
    {
        $this->register(new EventProvider());
        $this->register(new ConfigProvider($this->basePath, $this->srcPath));
        $this->register(new ViewProvider());
        $this->register(new HttpProvider());
        $this->register(new ConsoleProvider());
        $this->register(new PluginProvider());
        // $this->register(new TestProvider());
    }

    public function run()
    {
        // run the appropriate handler
        try {
            // register all services
            foreach($this->providers as $provider) {
                $provider->register($this->container);
            }
            $this->container->make('prontotype.events')->emit(Event::named('services.register.end'));

            // boot all services
            foreach($this->providers as $provider) {
                $provider->boot($this->container);
            }
            $this->container->make('prontotype.events')->emit(Event::named('services.boot.end'));

            $handler = $this->isRunningInConsole() ? $this->container->make('prontotype.console') : $this->container->make('prontotype.http');
            return $handler->run();
        } catch (\Exception $e) {
            echo 'An application error has occured: ' . $e->getMessage();
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