<?php

namespace DevUri\Config\App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InstallerCommand extends Command
{
    protected static $defaultName = 'install:wp';

    private $wp_path;
    private $admin_user;
    private $password;
    private $admin_email;

    public function __construct( $root_dir_path )
    {
        parent::__construct();
        $this->wp_path = $root_dir_path . '/public/wp/';
        $this->site_title = strtoupper(self::generate_password( 5 ));
        $this->admin_user = 'admin' . self::generate_password( 6 );
        $this->admin_password = self::generate_password( 12 );
        $this->admin_email = 'admin@' . self::generate_password( 8 ) . '.com';
    }

    protected function configure(): void
    {
        $this->setDescription( 'Install WordPress' )
            ->addArgument( 'site_title', InputArgument::REQUIRED, 'Site title', null )
            ->addArgument( 'admin_user', InputArgument::VALUE_REQUIRED, 'Admin username', null )
            ->addArgument( '_password', InputArgument::VALUE_REQUIRED, 'Admin password', null );
    }

    protected function execute( InputInterface $input, OutputInterface $output ): int
    {
        // Include the necessary WordPress files
        require_once $this->wp_path . '/wp-load.php';
        require_once $this->wp_path . '/wp-admin/includes/upgrade.php';
        require_once $this->wp_path . '/wp-includes/wp-db.php';

        // Get input arguments
        $site_title     = $input->getArgument( 'site_title' ) ?? $this->site_title;
        $admin_user     = $input->getArgument( 'admin_user' ) ?? $this->admin_user;
        $admin_password = $input->getArgument( '_password' ) ?? $this->admin_password;

        // Install WordPress
        $install = wp_install(
            $site_title,
            $admin_user,
            $this->admin_email,
            true,
            '',
            $admin_password,
            false
        );

        if ( ! $install ) {
            $output->writeln( '<error>Install failed </error>' );

            return Command::FAILURE;
        }

        $output->writeln( '<info>WordPress Installed Successfully!</info>' );
        $output->writeln( "<info>site: $site_title</info> ");
        $output->writeln( "<info>user: $site_title</info> ");
        $output->writeln( "<info>password: $admin_password</info> ");

        return Command::SUCCESS;
    }

    /**
     * Generate a random alphanumeric password of a specified length, starting with a letter.
     *
     * @param int $length The length of the password to generate.
     *
     * @return string The generated password.
     */
    private static function generate_password( int $length = 8 ): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password   = '';
        for ( $i = 0; $i < $length; $i++ ) {
            if ( 0 === $i ) {
                $password .= $characters[ rand( 0, 51 ) ];
            // First character must be a letter
            } else {
                $password .= $characters[ rand( 0, 61 ) ];
                // Any character
            }
        }

        return $password;
    }
}
