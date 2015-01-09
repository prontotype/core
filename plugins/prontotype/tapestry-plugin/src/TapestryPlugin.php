<?php namespace Prontotype\Plugins\Tapestry;

use Prontotype\Event;
use Prontotype\Plugins\AbstractPlugin;
use Prontotype\Plugins\PluginInterface;

class TapestryPlugin extends AbstractPlugin implements PluginInterface
{
    public function getConfig()
    {
        return 'config/config.yml';
    }

    public function register()
    {
        $conf = $this->container->make('prontotype.config');

        $conf = $this->container->make('prontotype.config');
        $handler = $this->container->make('prontotype.http');
        $events = $this->container->make('prontotype.events');
        $loader = $this->container->make('prontotype.view.loader');

        $conf->set('tapestry.assetspath', realpath($this->path . '/assets'));

        $events->emit(Event::named('tapestry.register.start'));

        $globals = array();

        $this->container->alias('tapestry.repo.markup', 'Prontotype\Plugins\Tapestry\Repositories\MarkupRepository')->share('tapestry.repo.markup');

        $loader->addPath(make_path($this->path, '/templates'), 'tapestry'); // doto: split out into config

        $this->setRoutes($handler, $events);
      
        $globals['markup'] = $this->container->make('tapestry.repo.markup');
        $globals['config'] = $conf->get('tapestry');
                
        $this->container->make('prontotype.view.globals')->add('tapestry', $globals);

        $events->emit(Event::named('tapestry.register.end'));
    }

    public function getGlobals()
    {
        return array(
            
        );
    }

    protected function setRoutes($handler, $events)
    {
        $handler->get('/', 'Prontotype\Plugins\Tapestry\Controllers\TapestryController::index')
            ->name('tapestry.index');

        // markup
        
        $handler->get('/markup', 'Prontotype\Plugins\Tapestry\Controllers\TapestryController::markupIndex')
            ->name('tapestry.markup.index');

        $handler->get('/markup/{path}.html/preview', 'Prontotype\Plugins\Tapestry\Controllers\MarkupController::preview')
            ->name('tapestry.markup.preview')
            ->assert('path', '.+');

        $handler->get('/markup/{path}.html/raw', 'Prontotype\Plugins\Tapestry\Controllers\MarkupController::raw')
            ->name('tapestry.markup.raw')
            ->assert('path', '.+');

        $handler->get('/markup/{path}.html/download', 'Prontotype\Plugins\Tapestry\Controllers\MarkupController::download')
            ->name('tapestry.markup.download')
            ->assert('path', '.+');

        $handler->get('/markup/{path}.html/highlight', 'Prontotype\Plugins\Tapestry\Controllers\MarkupController::highlight')
            ->name('tapestry.markup.highlight')
            ->assert('path', '.+');

        $handler->get('/markup/{path}.html', 'Prontotype\Plugins\Tapestry\Controllers\MarkupController::detail')
            ->name('tapestry.markup.detail')
            ->assert('path', '.+');

        // assets
        
        $handler->get('/assets', 'Prontotype\Plugins\Tapestry\Controllers\TapestryController::assetsIndex')
            ->name('tapestry.assets.index'); 

        // static 
        
        $handler->get('/__tapestry/assets/{assetPath}', 'Prontotype\Plugins\Tapestry\Controllers\TapestryController::serveAsset')
            ->name('tapestry.static.asset')
            ->assert('assetPath', '.+');

        // catchall is to throw a not found error
        $events->addListener('appRoutes.register.start', function() use ($handler) {
            $handler->get('/{templatePath}', 'Prontotype\Http\Controllers\DefaultController::notFound')
                ->name('tapsetry.catchall')
                ->value('templatePath', '/')
                ->assert('templatePath', '[^:]+');
        });
    }

}