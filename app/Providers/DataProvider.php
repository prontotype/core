<?php namespace Prontotype\Providers;

use Auryn\Provider as Container;

use Prontotype\Config;
use Amu\Dayglo\Parser;
use Amu\Dayglo\Loader;
use Amu\Dayglo\ParserCollection;

class DataProvider implements ProviderInterface
{
    public function register(Container $container)
    {
        $conf = $container->make('prontotype.config');
        
        $parsers = new ParserCollection([
            new Parser\JsonParser(),
            new Parser\YamlParser(),
            new Parser\CsvParser(),
            new Parser\TomlParser(),
            new Parser\PhpParser(),
        ]);
        $container->share(new Loader($parsers, $conf->get('data.directory')));
    }
}