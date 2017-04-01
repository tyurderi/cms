<?php

namespace CMS\Commands;

use CMS\Components\Command;
use CMS\Components\Plugin\Manager;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PluginInstallCommand extends Command
{

    public function configure()
    {
        $this->setName('plugin:install')
            ->setDescription('Install a plugin.');

        $this->addArgument('name', InputArgument::REQUIRED, 'The case-sensitive name of the plugin.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $manager = new Manager();
        $name    = trim($input->getArgument('name'));
        $result  = $manager->install($name);

        if ($result === true || is_array($result) && isset($result['success']) && $result['success'] === true)
        {
            $output->writeln('The plugin were installed successfully.');
        }
        else
        {
            $output->writeln('Unable to install the plugin!');
            $output->writeln(json_encode($result, JSON_PRETTY_PRINT));
        }
    }

}