<?php

namespace PluginManager;

use BackendMenu\Models\BackendMenu;
use Favez\Mvc\Event\Arguments;

class Bootstrap extends \CMS\Components\Plugin\Bootstrap
{
    
    public function install()
    {
        $this->createMenu([
            'label'       => 'PluginManager',
            'url'         => '/pluginManager',
            'icon'        => 'puzzle-piece',
            'permissions' => 'plugin.list',
            'position'    => 2
        ]);
        
        return true;
    }
    
    public function update($oldVersion)
    {
        switch ($oldVersion)
        {
            /**
             * 1. Rename menu entry from 'PluginManager' to 'Plugins'
             */
            case '1.0.0': {
                $menu = BackendMenu::findOneBy([
                    'label'    => 'PluginManager',
                    'pluginID' => $this->instance->getModel()->id
                ]);
                
                if ($menu instanceof BackendMenu)
                {
                    $menu->label = 'Plugins';
                    $menu->save();
                }
            } break;
        }
        
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