<?php

namespace Urisoft\App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Urisoft\App\Console\Traits\Env;
use Urisoft\App\Console\Traits\Generate;
use Symfony\Component\Filesystem\Filesystem;

class ConfigCommand extends Command
{
    use Env;
    use Generate;

    protected static $defaultName = 'config';

    private $root_dir_path;
    private $filesystem;

    public function __construct( string $root_dir_path, Filesystem $filesystem  )
    {
        parent::__construct();
		$this->filesystem    = $filesystem;
        $this->root_dir_path = $root_dir_path;
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
            $output->writeln( "<info>Config Setup for:$this->root_dir_path</info>" );

            return Command::SUCCESS;
        }

		if ( 'create-public-key' === $config_task ) {
            $key_name = $this->create_uuid_key_file();
            $output->writeln( PHP_EOL . "<comment>Public key was created (remember to add your actual key the file contains an example key): </comment><info>$key_name</info>" . PHP_EOL );

            return Command::SUCCESS;
        }

        if ( 'uuid' === $config_task ) {
            $uuid = self::uuid();
            $output->writeln( PHP_EOL . "<comment>The uuid is: </comment><info>$uuid</info>" . PHP_EOL );

            return Command::SUCCESS;
        }

        // Htpasswd Generator to create htpasswd.
        if ( 'htpass' === $config_task || 'sechtpass' === $config_task ) {
            $this->create_htpasswd_file( $config_task, $output );

            return Command::SUCCESS;
        }

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
