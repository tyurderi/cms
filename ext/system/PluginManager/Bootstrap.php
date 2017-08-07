<?php

namespace PluginManager;

use BackendMenu\Models\BackendMenu;
use Favez\Mvc\Event\Arguments;

class Bootstrap extends \CMS\Components\Plugin\Bootstrap
{
    
    public function install()
    {
        $this->createMenu([
            'label'       => 'Plugins',
            'url'         => '/pluginManager',
            'icon'        => 'puzzle-piece',
            'permissions' => 'plugin.list',
            'position'    => 2
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