<?php

namespace Urisoft\App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

class Installer extends Command
{
    protected static $defaultName = 'wp:install';

    private $root_dir_path;
    private $filesystem;

    // wp:install -u admin
    public function __construct( string $root_dir_path, Filesystem $filesystem )
    {
        parent::__construct();
        $this->filesystem    = $filesystem;
        $this->root_dir_path = $root_dir_path;
    }

    protected function configure(): void
    {
        $this->setDescription( 'Installs WordPress' )
            ->addOption( 'user', 'u', InputOption::VALUE_REQUIRED, 'The admin username.', 'admin' )
            ->addOption( 'email', 'e', InputOption::VALUE_REQUIRED, 'The admin email address.', 'admin@example.com' )
            ->setHelp( 'This command installs WordPress.' );
    }

    /**
     * @return int
     *
     * @psalm-return 0|1
     */
    protected function execute( InputInterface $input, OutputInterface $output )
    {
        $io = new SymfonyStyle( $input, $output );

        $username = $input->getOption( 'user' );
        $email = $input->getOption( 'email' );

        $io->title( 'Starting WordPress installation...' );

        $output->writeln( 'installation started...' );

        // handle installation.
        $result = self::wp_installer(
            $this->root_dir_path . '/public/wp/',
            [
                'user_name' => $username,
                'user_email' => $email,
            ]
        );

        if ( $result ) {
            $io->newLine();
            $io->success( 'WordPress installation completed successfully.' );
            $io->section( 'Login information:' );
            $io->newLine();
            $io->info(
                [
                    'User ID: ' . $result['user_id'],
                    'Username: ' . $result['user_name'],
                    'Password: ' . $result['user_password'],
                    'Email: ' . $result['user_email'],
                ]
            );
            $io->newLine();
            $io->note( 'Be sure to change the login info before deploying to a production environment.' );
            $io->newLine();

            return Command::SUCCESS;
        }

        $io->warning( 'WordPress is already installed.' );

        return Command::FAILURE;
    }

    /**
     * Run the WordPress Quick Installer.
     *
     * @param string $wp_dir_path The WordPress directory path.
     * @param array  $params      Optional installation parameters.
     *                            Default values will be used if not provided.
     *
     * @return array
     */
    protected static function wp_installer( string $wp_dir_path, array $params = [] ): ?array
    {
        $installer = new QuickInstaller( $wp_dir_path, $params );

        // Run the installation
        $run = $installer->install();

        if ( ! \is_null( $run ) ) {
            return $installer->get_params();
        }

        return null;
    }
}
