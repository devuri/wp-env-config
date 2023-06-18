<?php

namespace Urisoft\App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Urisoft\App\Console\Traits\Env;
use Urisoft\App\Console\Traits\Generate;

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

        // Htpasswd Generator to create htpasswd.
        $this->create_htpasswd_file( $config_task, $output );

        // $output->writeln( "<comment>$config_task is not a valid config task</comment>" );

        return Command::SUCCESS;
    }

    protected function create_htpasswd_file( string $config_task, OutputInterface $output ): void
    {
        // create passwords for htpasswd files.
        if ( 'htpass' === $config_task ) {
            $password = self::rand_str();

            // just use `demo` user.
            $htpasswd = self::htpasswd( 'demo', $password );
            $output->writeln( PHP_EOL . "<comment>Htpasswd Generator:username and password:</comment><info>demo $password</info>" . PHP_EOL );
            $output->writeln( PHP_EOL . "<comment>Add to htpasswd file:</comment><info>$htpasswd</info>" . PHP_EOL );
            $output->writeln( PHP_EOL . '<comment>* Apache specific salted MD5 (can be insecure but is very common), use `sechtpass` to generate more secure password </comment>' . PHP_EOL );
        } elseif ( 'sechtpass' ) {
            $password = self::rand_str();

            // just use `demo` user.
            $htpasswd = self::bcr_htpasswd( 'demo', $password );
            $output->writeln( PHP_EOL . "<comment>Htpasswd Generator:username and password:</comment><info>demo $password</info>" . PHP_EOL );
            $output->writeln( PHP_EOL . "<comment>Add to htpasswd file:</comment><info>$htpasswd</info>" . PHP_EOL );
            $output->writeln( PHP_EOL . '<comment>* Bcrypt for Apache v2.4 onwards</comment>' . PHP_EOL );
        }
    }
}
