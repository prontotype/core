<?php namespace Prontotype\Providers;

use Prontotype\Container;
use Prontotype\Config;
use League\Event\Event;
use DirectoryIterator;

class PluginProvider implements ProviderInterface
{
    protected $plugins = array();

    public function register(Container $container)
    {
        $conf = $container->make('prontotype.config');
        $globals = $container->make('prontotype.view.globals');
        $this->plugins = $this->getPluginDefinitions($conf->get('prontotype.plugins'), $container);

        foreach($this->plugins as $plugin) {
            $this->loadPluginConfig($plugin, $container);
        }
        $container->make('prontotype.events')->emit(Event::named('plugins.configured'));
    }

    public function boot(Container $container)
    {
        foreach($this->plugins as $plugin) {
            $this->initPlugin($plugin, $container);
        }
        $container->make('prontotype.events')->emit(Event::named('plugins.loaded'));
    }

    protected function getPluginDefinitions($pluginDir, &$container)
    {
        $plugins = array();
        
        foreach (new DirectoryIterator($pluginDir) as $fileInfo) {
            if ( ! $fileInfo->isDot() ) {
                foreach (new DirectoryIterator($fileInfo->getPathname()) as $fileInfo) {
                    if ( ! $fileInfo->isDot() ) {
                        if ( file_exists(make_path($fileInfo->getPathname(), 'composer.json')) ) {
                            $composerConfig = json_decode(file_get_contents(make_path($fileInfo->getPathname(), 'composer.json')), true);
                            if ( isset($composerConfig['extra']['provider'])) {
                                $plugin = array(
                                    'path' => $fileInfo->getPathname(),
                                    'composerConfig' => $composerConfig,
                                    'plugin' => new $composerConfig['extra']['provider']($fileInfo->getPathname(), $container)
                                );
                                $plugins[] = $plugin;
                            }
                        }
                    }
                }
            }
        }
        return $plugins;
    }

    protected function loadPluginConfig($pluginDefinition, Container &$container)
    {
        $config = $container->make('prontotype.config');
        $plugin = $pluginDefinition['plugin'];
        $pluginConfig = $plugin->getConfig();
        if (is_string($pluginConfig)) {
            $config->mergeWithfile(make_path($pluginDefinition['path'], $pluginConfig));
        } else {
            $config->merge($pluginConfig);
        }
    }

    protected function initPlugin($pluginDefinition, Container &$container)
    {   
        $globals = $container->make('prontotype.view.globals');
        $plugin = $pluginDefinition['plugin'];

        $plugin->register();

        foreach($plugin->getGlobals() as $key => $value) {
            $globals->add($key, $value);
        }
        
    }
}