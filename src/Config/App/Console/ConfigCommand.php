<?php

namespace DevUri\Config\App\Console;

use DevUri\Config\App\Console\Traits\Env;
use DevUri\Config\App\Console\Traits\Generate;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigCommand extends Command
{
    use Env;
    use Generate;

    protected static $defaultName = 'config';

    private $root_dir_path;
    private $http_app;

    public function __construct( string $root_dir_path, ?object $app = null )
    {
        parent::__construct();
        $this->root_dir_path = $root_dir_path;
        $this->http_app      = $app;
    }

    protected function configure(): void
    {
        $this->setDescription( 'Display a list of constants defined by Setup.' )
            ->addArgument( '_task', InputArgument::OPTIONAL, 'The task that nino needs to perform.', false );
    }

    /**
     * @return int
     */
    protected function execute( InputInterface $input, OutputInterface $output ): int
    {
        $config_task = $input->getArgument( '_task' );

        if ( false === $config_task ) {
            $output->writeln( "<info>Config Setup:$this->root_dir_path</info>" );
            dump( $this->get_env( $_ENV ) );
        }

        if ( 'uuid' === $config_task ) {
            $uuid = self::uuid();
            $output->writeln( PHP_EOL . "<comment>The uuid is: </comment><info>$uuid</info>" . PHP_EOL );
        }

        // $output->writeln( "<comment>$config_task is not a valid config task</comment>" );

        return Command::SUCCESS;
    }
}
