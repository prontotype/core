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
        $config = $this->container->make('prontotype.config');

        $config->set('tapestry.assetspath', make_path($this->path, 'assets'));
        $config->set('tapestry.tplpath', make_path($this->path, 'templates'));

        $config->set('tapestry.src.docs', make_path($config->get('prontotype.basepath'), $config->get('tapestry.docs.directory')));
        $config->set('tapestry.src.markup', $config->get('tapestry.markup.directory') ? make_path($config->get('prontotype.basepath'), $config->get('tapestry.markup.directory')) : $config->get('templates.directory'));

        $this->container->alias('tapestry.repo.markup', 'Prontotype\Plugins\Tapestry\Repositories\MarkupRepository')->share('tapestry.repo.markup');   
        $this->container->alias('tapestry.repo.docs', 'Prontotype\Plugins\Tapestry\Repositories\DocsRepository')->share('tapestry.repo.docs');
        $this->container->alias('tapestry.view', 'Prontotype\Plugins\Tapestry\ViewHelper')->share('tapestry.view');

        $this->container->make('prontotype.view.loader')->addPath($config->get('tapestry.tplpath'), 'tapestry');
        $this->container->make('prontotype.view.loader')->addPath($config->get('tapestry.src.markup'));
       
        $this->setRoutes();
        
        $this->container->make('prontotype.events')->emit(Event::named('tapestry.register.end'));
    }

    public function getGlobals()
    {
        $config = $this->container->make('prontotype.config');
        return array(
            'tapestry' => array(
                'markup' => $this->container->make('tapestry.repo.markup'),
                'docs' => $this->container->make('tapestry.repo.docs'),
                'view'   => $this->container->make('tapestry.view'),
                'config' => $this->container->make('prontotype.config')->get('tapestry'),
                'has' => array(
                    'docs' => file_exists($config->get('tapestry.src.docs')),
                    'markup' => file_exists($config->get('tapestry.src.markup'))
                )
            )
        );
    }

    protected function setRoutes()
    {
        $config = $this->container->make('prontotype.config');
        $handler = $this->container->make('prontotype.http');
        $events = $this->container->make('prontotype.events');
        
        $handler->get('/', 'Prontotype\Plugins\Tapestry\Controllers\TapestryController::index')
            ->name('tapestry.index');

        // markup
        
        if ( file_exists($config->get('tapestry.src.markup')) ) {

            $markupUrl = '/' . $config->get('tapestry.markup.url');
        
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
        }

        // assets
                
        $assetsUrl = '/' . $config->get('tapestry.assets.url');
        
        $handler->get($assetsUrl, 'Prontotype\Plugins\Tapestry\Controllers\TapestryController::assetsIndex')
            ->name('tapestry.assets.index');


        if ( file_exists($config->get('tapestry.src.docs')) ) {
        
            $docsUrl = '/' . $config->get('tapestry.docs.url');
            
            $handler->get($docsUrl . '/{path}', 'Prontotype\Plugins\Tapestry\Controllers\DocsController::page')
                ->name('tapestry.docs.page')
                ->assert('path', '.+');

            $handler->get($docsUrl, 'Prontotype\Plugins\Tapestry\Controllers\DocsController::index')
                ->name('tapestry.docs.index');

        }

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