<?php namespace Prontotype\Providers;

use Prontotype\Event;
use Prontotype\Container;

class TapestryProvider implements ProviderInterface
{
    public function register(Container $container)
    {
        $this->container = $container;

        $conf = $this->container->make('prontotype.config');
        $handler = $this->container->make('prontotype.http');
        $events = $this->container->make('prontotype.events');
        $loader = $container->make('prontotype.view.loader');
        
        $events->emit(Event::named('tapestry.register.start'));

        $loader->addPath(make_path($conf->get('prontotype.srcpath'), 'tapestry/templates'), 'tapestry'); // doto: split out into config


        $handler->get('/', 'Prontotype\Http\Controllers\TapestryController::index')
            ->name('tapestry.index');

        // markup
        
        $handler->get('/markup', 'Prontotype\Http\Controllers\TapestryController::markupIndex')
            ->name('tapestry.markup.index');

        $handler->get('/markup/{path}.html/preview', 'Prontotype\Http\Controllers\MarkupController::preview')
            ->name('tapestry.markup.preview')
            ->assert('path', '.+');

        $handler->get('/markup/{path}.html/raw', 'Prontotype\Http\Controllers\MarkupController::raw')
            ->name('tapestry.markup.raw')
            ->assert('path', '.+');

        $handler->get('/markup/{path}.html/download', 'Prontotype\Http\Controllers\MarkupController::download')
            ->name('tapestry.markup.download')
            ->assert('path', '.+');

        $handler->get('/markup/{path}.html', 'Prontotype\Http\Controllers\MarkupController::detail')
            ->name('tapestry.markup.detail')
            ->assert('path', '.+');

        // assets
        
        $handler->get('/assets', 'Prontotype\Http\Controllers\TapestryController::assetsIndex')
            ->name('tapestry.assets.index'); 

        // catchall is to throw a not found error
        $events->addListener('appRoutes.register.start', function() use ($handler) {
            $handler->get('/{templatePath}', 'Prontotype\Http\Controllers\DefaultController::notFound')
                ->name('tapsetry.catchall')
                ->value('templatePath', '/')
                ->assert('templatePath', '[^:]+');
        });
        

        $events->emit(Event::named('tapestry.register.end'));
    }

    public function boot(Container $container)
    {

    }
}
