<?php

namespace PluginManager;

use Favez\Mvc\Event\Arguments;

class Bootstrap extends \CMS\Components\Plugin\Bootstrap
{
    
    public function install()
    {
        $this->createMenu([
            'label' => 'PluginManager',
            'url'   => '/pluginManager'
        ]);
        
        return true;
    }
    
    public function execute()
    {
        $this->subscribeEvent('vue.collector.run', [$this, 'onVueCollectorRun']);
    
        $this->registerController('Api', 'Plugin');
    }
    
    public function onVueCollectorRun(Arguments $args)
    {
        /** @var \CMS\Components\Collector\Vue $collector */
        $collector = $args->get('collector');
        $collector->pushAlias(
            '@PluginManager',
            $this->getRelativePath() . 'Views/backend/src/',
            $this->getPath() . 'Views/backend/src'
        );
    }
    
}