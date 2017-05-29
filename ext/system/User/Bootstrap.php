<?php

namespace User;

use Favez\Mvc\Event\Arguments;

class Bootstrap extends \CMS\Components\Plugin\Bootstrap
{
    
    public function install()
    {
        $menu = $this->createMenu([
            'label' => 'Users',
            'url'   => '/users',
            'icon'  => 'user'
        ]);
    
        $this->createMenu([
            'label'    => 'Groups',
            'url'      => '/users/groups',
            'icon'     => 'users',
            'parentID' => $menu->id,
        ]);
    
        $this->createMenu([
            'label'    => 'Permissions',
            'url'      => '/users/permissions',
            'icon'     => 'file-text',
            'parentID' => $menu->id,
        ]);
        
        return true;
    }
    
    public function execute()
    {
        $this->subscribeEvent('vue.collector.run', [$this, 'onVueCollectorRun']);
        
        $this->registerController('Api', 'User');
        $this->registerController('Api', 'Group');
    }
    
    public function onVueCollectorRun(Arguments $args)
    {
        /** @var \CMS\Components\Collector\Vue $collector */
        $collector = $args->get('collector');
        $collector->pushAlias(
            '@User',
            $this->getRelativePath() . 'Views/backend/src/',
            $this->getPath() . 'Views/backend/src'
        );
    }
    
}