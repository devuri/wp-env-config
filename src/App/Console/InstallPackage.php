<?php

namespace Urisoft\App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class InstallPackage extends Command
{
    protected static $defaultName = 'n:i';

    protected function configure(): void
    {
        // php nino n:i -p theme brisko
        $this->setDescription( 'Add a plugin or theme via composer using slug only' );
        $this->addArgument( 'package', InputArgument::REQUIRED, 'The slug of the plugin or theme (example: brisko)' );
        $this->addOption( 'type', 'p', InputOption::VALUE_REQUIRED, 'Package type' );
    }

    /**
     * @return int
     *
     * @psalm-return 0|1
     */
    protected function execute( InputInterface $input, OutputInterface $output ): int
    {
        $package = $input->getArgument( 'package' );
        $_type   = $input->getOption( 'type' );

        if ( 'plugin' === $_type ) {
            $package_name = 'wpackagist-plugin/' . $package;
        } elseif ( 'theme' === $_type ) {
            $package_name = 'wpackagist-theme/' . $package;
        } else {
            $output->writeln( 'Please specify the package type using either --p for plugin or --t for theme.' );

            return Command::FAILURE;
        }

        // Run composer require to install the package
        $process = new Process( [ 'composer', 'require', $package_name ] );
        $process->setTty( true );
        $process->run();

        if ( $process->isSuccessful() ) {
            $output->writeln( 'Package "' . $package . '" installed successfully.' );

            return Command::SUCCESS;
        }
        $output->writeln( 'Package installation failed.' );

        return Command::FAILURE;
    }
}
