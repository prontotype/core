<?php namespace Prontotype\Providers;

use Prontotype\Config;
use Prontotype\Container;

class ConfigProvider implements ProviderInterface
{
    protected $configPath = 'config/config.yml';

    public function __construct($basePath, $srcPath)
    {
        $this->basePath = $basePath;
        $this->srcPath = $srcPath;
    }

    public function register(Container $container)
    {
        $events = $container->make('prontotype.events');
        $config = new Config([
            'prontotype' => [
                'basepath' => $this->basePath,
                'srcpath' => $this->srcPath
            ]
        ]);
        $config->mergeWithFile(make_path($this->srcPath, $this->configPath));
        $container->share($config)->alias('prontotype.config', 'Prontotype\Config');
        $events->addListener('plugins.config.loaded', function() use ($config){
            $config->mergeWithFile(make_path($this->basePath, $this->configPath));
        });
    }
}