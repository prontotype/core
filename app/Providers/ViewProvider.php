<?php namespace Prontotype\Providers;

use Twig_Environment;
use Auryn\Provider as Container;
use Prontotype\View\Twig\Environment;
use Prontotype\View\Twig\Loader as TemplateLoader;
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
        $container->alias('prontotype.view.loader', 'Prontotype\View\Twig\Loader');
        
        $twig = new Environment($container->make('prontotype.view.loader'), array(
            'strict_variables'  => false,
            'base_template_class' => 'Prontotype\View\Twig\Template',
            'cache'             => false,
            'auto_reload'       => true,
            'debug'             => $conf->get('debug'),
            'autoescape'        => false
        ));

        $twig->addExtension($container->make('Amu\Twig\TwigMarkdown\TwigMarkdownExtension'));
        $twig->addExtension($container->make('Prontotype\View\Twig\Extension\ProntotypeExtension'));
       
        $container->share($twig)->alias('prontotype.view.environment', 'Prontotype\View\Twig\Environment');       
    }
}
