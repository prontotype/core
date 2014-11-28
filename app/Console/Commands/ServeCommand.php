<?php namespace Prontotype\Console\Commands;

use Symfony\Component\Console\Input\InputOption;
use Prontotype\Console\Command;

class ServeCommand extends Command {

    public function __construct($basePath, $serverPath)
    {
        $this->basePath = $basePath;
        $this->serverPath = $serverPath;
        parent::__construct();
    }

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'serve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Serve the application on the PHP development server";

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->checkPhpVersion();

        chdir($this->basePath);
        
        $host = $this->input->getOption('host');
        $port = $this->input->getOption('port');

        $this->info("Development server started on http://{$host}:{$port}");

        passthru('"'.PHP_BINARY.'"'." -S {$host}:{$port} -t \"{$this->basePath}\" {$this->serverPath}");
    }

    /**
     * Check the current PHP version is >= 5.4.
     *
     * @return void
     *
     * @throws \Exception
     */
    protected function checkPhpVersion()
    {
        if (version_compare(PHP_VERSION, '5.4.0', '<')) {
            throw new \Exception('This PHP binary is not version 5.4 or greater.');
        }
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('host', null, InputOption::VALUE_OPTIONAL, 'The host address to serve the application on.', 'localhost'),
            array('port', null, InputOption::VALUE_OPTIONAL, 'The port to serve the application on.', 8000),
        );
    }

}