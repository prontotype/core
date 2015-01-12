<?php namespace Prontotype\Providers;

use Twig_Environment;
use Prontotype\Container;
use Prontotype\View\Twig\Environment;
use Prontotype\View\Twig\Loader as TemplateLoader;
use Prontotype\View\Twig\StringLoader as StringLoader;
use Prontotype\View\Twig\DataExtension;
use Amu\Twig\TwigMarkdown\TwigMarkdownExtension;

class ViewProvider implements ProviderInterface
{
    public function register(Container $container)
    {
        $conf = $container->make('prontotype.config');
        
        $container->define('Prontotype\View\Twig\Loader', [
            ':paths' => $conf->get('templates.directory'),
            ':defaultExt' => $conf->get('templates.extension')
        ]);
        $container->share('Prontotype\View\Twig\Loader')->alias('prontotype.view.loader', 'Prontotype\View\Twig\Loader');

        $container->share('Prontotype\View\Globals')->alias('prontotype.view.globals', 'Prontotype\View\Globals');
        $container->make('prontotype.view.globals')->add('config', $conf);
        
        $loader = $container->make('prontotype.view.loader');

        $cache = empty($conf->get('cache.directory')) || ! file_exists($conf->get('cache.directory')) ? false : $conf->get('cache.directory');
        $cacheAutoReload = ($cache && $conf->get('cache.auto_reload'));
        
        $twig = new Environment($loader, array(
            'strict_variables'  => false,
            'base_template_class' => 'Prontotype\View\Twig\Template',
            'debug' => true,
            'cache'             => $cache,
            'auto_reload'       => $cacheAutoReload,
            'autoescape'        => false
        ));

        $twig->addExtension($container->make('Amu\Twig\TwigMarkdown\TwigMarkdownExtension'));
        $twig->addExtension($container->make('Prontotype\View\Twig\Extension\ProntotypeExtension'));
        $twig->addExtension(new \Twig_Extension_Debug());
        
        $container->share($twig)->alias('prontotype.view.environment', 'Prontotype\View\Twig\Environment');
    }

    public function boot(Container $container)
    {
        
    }
}
