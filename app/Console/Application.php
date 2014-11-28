<?php namespace Prontotype\Console;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Prontotype\Console\Command;

class Application extends \Symfony\Component\Console\Application
{ 
    public function __construct()
    {
        parent::__construct('Prontotype');
    }

    public function call($command, array $parameters = array(), OutputInterface $output = null)
    {
        $parameters['command'] = $command;
        $output = $output ?: new NullOutput;
        $input = new ArrayInput($parameters);
        return $this->find($command)->run($input, $output);
    }

}