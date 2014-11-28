<?php namespace Prontotype;

use Auryn\ReflectionPool;
use Auryn\Provider as Injector;
use Prontotype\Config;
use Prontotype\Providers\ProviderInterface;
use Prontotype\Providers\HttpProvider;
use Prontotype\Providers\ViewProvider;
use Prontotype\Providers\ConfigProvider;
use Prontotype\Providers\ConsoleProvider;
use Prontotype\Providers\DataProvider;

class Application {

    protected $container;

    protected $basePath;

    protected $srcPath;

    protected $configPath = 'config/config.yml';

    protected $providers = [];

    public function __construct($basePath)
    {
        $this->container = new Injector(new ReflectionPool());
        $this->basePath = realpath($basePath);
        $this->srcPath = realpath(__DIR__ . '/..');
        $this->configure();
        $this->registerServices();
    }

    protected function configure()
    {
        $config = new Config([
            'prontotype' => [
                'basepath' => $this->basePath,
                'srcpath' => $this->srcPath
            ]
        ]);
        $srcConfig = make_path($this->srcPath, $this->configPath);
        $instanceConfig = make_path($this->basePath, $this->configPath);
        $config->mergeWithFile($srcConfig);
        $config->mergeWithFile($instanceConfig);
        $this->container->share($config)->alias('prontotype.config', 'Prontotype\Config');        
    }

    protected function registerServices()
    {
        $this->register(new DataProvider());
        $this->register(new ViewProvider());
        $this->register(new HttpProvider());
        $this->register(new ConsoleProvider());
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