<?php

namespace CMS\Commands;

use CMS\Components\Command;
use CMS\Components\Plugin\Instance;
use CMS\Components\Plugin\Manager;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PluginListCommand extends Command
{

    public function configure()
    {
        $this->setName('plugin:list')
            ->setDescription('Lists all available plugins.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $manager = new Manager();

        $manager->synchronize();

        $plugins = $manager->list();
        $table   = new Table($output);
        $table->setHeaders(['Name', 'Label', 'Version', 'Created', 'Updated', 'Active/Installed']);

        /** @var Instance $plugin */
        foreach ($plugins as $plugin)
        {
            $table->addRow([
                $plugin->getName(),
                $plugin->getInfo()->getLabel(),
                $plugin->getInfo()->getVersion(),
                $plugin->getModel()->created,
                $plugin->getModel()->changed,
                $plugin->getModel()->active ? 'Yes' : 'No'
            ]);
        }

        $output->writeln(count($plugins) . ' records found');
        $table->render();
    }

}