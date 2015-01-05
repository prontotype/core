<?php namespace Prontotype\Providers;

use Auryn\Provider as Container;
use Prontotype\Config;
use DirectoryIterator;

class PluginProvider implements ProviderInterface
{
    public function register(Container $container)
    {
        $conf = $container->make('prontotype.config');

        foreach (new DirectoryIterator($conf->get('prontotype.plugins')) as $fileInfo) {
            if ( ! $fileInfo->isDot() ) {
                foreach (new DirectoryIterator($fileInfo->getPathname()) as $fileInfo) {
                    if ( ! $fileInfo->isDot() ) {
                        if ( file_exists($fileInfo->getPathname() . '/composer.json') ) {
                            $composer = json_decode(file_get_contents($fileInfo->getPathname() . '/composer.json'), true);
                            if ( isset($composer['extra']['provider'])) {
                                $plugin = new $composer['extra']['provider']();
                                if ( file_exists($fileInfo->getPathname() . '/config/config.yml') ) {
                                    $conf->mergeWithFile($fileInfo->getPathname() . '/config/config.yml');
                                }
                                $plugin->register($container);
                            }
                        }
                    }
                }
            }
        }
    }
}