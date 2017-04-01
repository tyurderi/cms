<?php

namespace CMS\Commands;

use CMS\Components\Command;
use CMS\Components\Plugin\Manager;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PluginUninstallCommand extends Command
{

    public function configure()
    {
        $this->setName('plugin:uninstall')
            ->setDescription('Uninstall a plugin.');

        $this->addArgument('name', InputArgument::REQUIRED, 'The case-sensitive name of the plugin.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $manager = new Manager();
        $name    = trim($input->getArgument('name'));
        $result  = $manager->uninstall($name);

        if ($result === true || is_array($result) && isset($result['success']) && $result['success'] === true)
        {
            $output->writeln('The plugin were uninstalled successfully.');
        }
        else
        {
            $output->writeln('Unable to uninstall the plugin!');
            $output->writeln(json_encode($result, JSON_PRETTY_PRINT));
        }
    }

}