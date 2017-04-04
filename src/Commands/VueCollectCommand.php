<?php

namespace CMS\Commands;

use CMS\Components\Collector\Vue;
use CMS\Components\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class VueCollectCommand extends Command
{
    
    public function configure()
    {
        $this->setName('vue:collect')
            ->setDescription('Collects backend vue app information and returns a json response.');
    }
    
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $collector = new Vue();
        $collector->collect();
        
        $output->writeln(json_encode([
            'alias' => $collector->getAlias()
        ]));
    }
    
}