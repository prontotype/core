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
        $this->container->make('prontotype.events')->emit(Event::named('tapestry.register.start'));

        $this->container->alias('tapestry.repo.markup', 'Prontotype\Plugins\Tapestry\Repositories\MarkupRepository')->share('tapestry.repo.markup');
        $this->container->alias('tapestry.repo.docs', 'Prontotype\Plugins\Tapestry\Repositories\DocsRepository')->share('tapestry.repo.docs');
        $this->container->alias('tapestry.view', 'Prontotype\Plugins\Tapestry\ViewHelper')->share('tapestry.view');

        $this->container->make('prontotype.config')->set('tapestry.assetspath', make_path($this->path, 'assets'));
        $this->container->make('prontotype.view.loader')->addPath(make_path($this->path, 'templates'), 'tapestry');
        
        $this->setRoutes();
        
        $this->container->make('prontotype.events')->emit(Event::named('tapestry.register.end'));
    }

    public function getGlobals()
    {
        return array(
            'tapestry' => array(
                'markup' => $this->container->make('tapestry.repo.markup'),
                'docs' => $this->container->make('tapestry.repo.docs'),
                'view'   => $this->container->make('tapestry.view'),
                'config' => $this->container->make('prontotype.config')->get('tapestry')
            )
        );
    }

    protected function setRoutes()
    {
        $conf = $this->container->make('prontotype.config');
        $handler = $this->container->make('prontotype.http');
        $events = $this->container->make('prontotype.events');
        
        $handler->get('/', 'Prontotype\Plugins\Tapestry\Controllers\TapestryController::index')
            ->name('tapestry.index');

        // markup
        
        $markupUrl = '/' . $conf->get('tapestry.markup.url');
        
        $handler->get($markupUrl, 'Prontotype\Plugins\Tapestry\Controllers\TapestryController::markupIndex')
            ->name('tapestry.markup.index');

        $handler->get($markupUrl . '/{path}.html/preview', 'Prontotype\Plugins\Tapestry\Controllers\MarkupController::preview')
            ->name('tapestry.markup.preview')
            ->assert('path', '.+');

        $handler->get($markupUrl . '/{path}.html/raw', 'Prontotype\Plugins\Tapestry\Controllers\MarkupController::raw')
            ->name('tapestry.markup.raw')
            ->assert('path', '.+');

        $handler->get($markupUrl . '/{path}.html/download', 'Prontotype\Plugins\Tapestry\Controllers\MarkupController::download')
            ->name('tapestry.markup.download')
            ->assert('path', '.+');

        $handler->get($markupUrl . '/{path}.html/highlight', 'Prontotype\Plugins\Tapestry\Controllers\MarkupController::highlight')
            ->name('tapestry.markup.highlight')
            ->assert('path', '.+');
        
        $handler->get($markupUrl . '/{path}.html', 'Prontotype\Plugins\Tapestry\Controllers\MarkupController::detail')
            ->name('tapestry.markup.detail')
            ->assert('path', '.+');

        // assets
        
        $assetsUrl = '/' . $conf->get('tapestry.markup.url');
        
        $handler->get($assetsUrl, 'Prontotype\Plugins\Tapestry\Controllers\TapestryController::assetsIndex')
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