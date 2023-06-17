<?php

namespace DevUri\Config\App\Console;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class InstallPackage extends Command
{
    protected static $defaultName = 'install';

    protected function configure()
    {
        $this->setDescription('Add a plugin or theme via composer');
        $this->addArgument('package', InputArgument::REQUIRED, 'The slug of the plugin or theme (example: brisko)');
        $this->addOption('p', null, InputOption::VALUE_NONE, 'Install a plugin');
        $this->addOption('t', null, InputOption::VALUE_NONE, 'Install a theme');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $package = $input->getArgument('package');

        if ($input->getOption('p')) {
            $package_name = 'wpackagist-plugin/' . $package;
        } elseif ($input->getOption('t')) {
            $package_name = 'wpackagist-theme/' . $package;
        } else {
            $output->writeln('Please specify the package type using either --p for plugin or --t for theme.');
            return;
        }

        // Run composer require to install the package
        $process = new Process(['composer', 'require', $package_name]);
        $process->setTty(true);
        $process->run();

        if ($process->isSuccessful()) {
            $output->writeln('Package "'.$package.'" installed successfully.');
        } else {
            $output->writeln('Package installation failed.');
        }
    }
}
