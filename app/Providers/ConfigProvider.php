<?php namespace Prontotype\Providers;

use Prontotype\Config;
use Auryn\Provider as Container;

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
        $config = new Config([
            'prontotype' => [
                'basepath' => $this->basePath,
                'srcpath' => $this->srcPath
            ]
        ]);
        $config->mergeWithFile(make_path($this->srcPath, $this->configPath));
        $config->mergeWithFile(make_path($this->basePath, $this->configPath));
        $container->share($config)->alias('prontotype.config', 'Prontotype\Config');   
    }
}