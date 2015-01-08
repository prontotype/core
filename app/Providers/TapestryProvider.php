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

        $globals = array();

        $this->container->alias('tapestry.repo.markup', 'Prontotype\Tapestry\Repositories\MarkupRepository')->share('tapestry.repo.markup');

        $loader->addPath(make_path($conf->get('prontotype.srcpath'), 'tapestry/templates'), 'tapestry'); // doto: split out into config

        $this->setRoutes($handler, $events);
      

        $globals['markup'] = $this->container->make('tapestry.repo.markup');
        $globals['config'] = $conf->get('tapestry');
                
        $container->make('prontotype.view.globals')->add('tapestry', $globals);

        $events->emit(Event::named('tapestry.register.end'));
    }

    public function setRoutes($handler, $events)
    {
        $handler->get('/', 'Prontotype\Tapestry\Controllers\TapestryController::index')
            ->name('tapestry.index');

        // markup
        
        $handler->get('/markup', 'Prontotype\Tapestry\Controllers\TapestryController::markupIndex')
            ->name('tapestry.markup.index');

        $handler->get('/markup/{path}.html/preview', 'Prontotype\Tapestry\Controllers\MarkupController::preview')
            ->name('tapestry.markup.preview')
            ->assert('path', '.+');

        $handler->get('/markup/{path}.html/raw', 'Prontotype\Tapestry\Controllers\MarkupController::raw')
            ->name('tapestry.markup.raw')
            ->assert('path', '.+');

        $handler->get('/markup/{path}.html/download', 'Prontotype\Tapestry\Controllers\MarkupController::download')
            ->name('tapestry.markup.download')
            ->assert('path', '.+');

        $handler->get('/markup/{path}.html/highlight', 'Prontotype\Tapestry\Controllers\MarkupController::highlight')
            ->name('tapestry.markup.highlight')
            ->assert('path', '.+');

        $handler->get('/markup/{path}.html', 'Prontotype\Tapestry\Controllers\MarkupController::detail')
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
    }

    public function boot(Container $container)
    {

    }
}
