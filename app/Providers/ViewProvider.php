<?php namespace Prontotype\Providers;

use Twig_Environment;
use Auryn\Provider as Container;
use Prontotype\View\Twig\Loader as TemplateLoader;
use Prontotype\View\Twig\DataExtension;
use Amu\Twig\TwigMarkdown\TwigMarkdownExtension;

class ViewProvider implements ProviderInterface
{
    public function register(Container $container)
    {
        $conf = $container->make('prontotype.config');

        $loader = new TemplateLoader($conf->get('templates.directory'));
        $loader->setDefaultExtension($conf->get('templates.extension'));

        $twig = new Twig_Environment($loader, array(
            'strict_variables'  => false,
            'base_template_class' => 'Prontotype\View\Twig\Template',
            'cache'             => null,
            'auto_reload'       => true,
            'debug'             => $conf->get('debug'),
            'autoescape'        => false
        ));
        $twig->addExtension($container->make('Amu\Twig\TwigMarkdown\TwigMarkdownExtension'));
        $twig->addExtension($container->make('Prontotype\View\Twig\Prontotype\ProntotypeExtension'));
       
        $container->share($twig)->alias('prontotype.view.environment', 'Twig_Environment');

        $container->alias('prontotype.view.finder', 'Prontotype\View\TemplateFinder');
    }
}
