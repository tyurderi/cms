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
        $vue = new Vue();
        $vue->collect();
        
        $this->writeJSON($vue);
        $this->writeJS($vue);
        
        $output->writeln(json_encode(['success' => true]));
    }
    
    protected function writeJSON(Vue $vue)
    {
        $filename = self::path() . '/themes/backend/default/src/plugins.json';
        $data     = json_encode(['alias' => $vue->getAlias()]);
        
        file_put_contents($filename, $data);
    }
    
    protected function writeJS(Vue $vue)
    {
        $filename         = self::path() . '/themes/backend/default/src/plugins.js';
        $importStatements = [];
    
        foreach ($vue->getAlias() as $alias => $paths)
        {
            $importStatements[] = sprintf('import \'%s\';', $alias);
        }
    
        file_put_contents($filename, implode(PHP_EOL, $importStatements));
    }
    
}