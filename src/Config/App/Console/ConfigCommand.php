<?php

namespace DevUri\Config\App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigCommand extends Command
{
    protected static $defaultName = 'config';

    private $root_dir_path;

    public function __construct( $root_dir_path )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription( 'Display a list of constants defined by Setup.' )
            ->addArgument( '_task', InputArgument::OPTIONAL, 'The task that nino needs to perform.', false );
    }

    protected function execute( InputInterface $input, OutputInterface $output ): int
    {
        $config_task = $input->getArgument( '_task' );

        if ( false === $config_task ) {
            $output->writeln( '<info>Config Setup:</info>' );
            dump( $_ENV );
        } else {
            $output->writeln( "<comment>$config_task is not a valid config task</comment>" );
        }

        return Command::SUCCESS;
    }
}
