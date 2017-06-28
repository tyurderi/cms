<?php

namespace Config;

use Favez\Mvc\Event\Arguments;

class Bootstrap extends \CMS\Components\Plugin\Bootstrap
{
    
    public function install()
    {
        $menu = $this->createMenu([
            'label'       => 'Settings',
            'url'         => '/config',
            'position'    => -1,
            'icon'        => 'cog',
            'permissions' => ''
        ]);
        
        $this->createMenu([
            'label'       => 'Sites',
            'url'         => '/config/sites',
            'position'    => 0,
            'icon'        => 'files-o',
            'permissions' => '',
            'parentID'    => $menu->id
        ]);
        
        return true;
    }
    
    public function execute()
    {
        $this->registerController('Api', 'Site');
        
        $this->subscribeEvent('vue.collector.run', [$this, 'onVueCollectorRun']);
    }
    
    public function onVueCollectorRun(Arguments $args)
    {
        /** @var \CMS\Components\Collector\Vue $collector */
        $collector = $args->get('collector');
        $collector->pushAlias(
            '@Config',
            $this->getRelativePath() . 'Views/backend/src/',
            $this->getPath() . 'Views/backend/src'
        );
    }
    
}