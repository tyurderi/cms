<?php

namespace Themes;

use BackendMenu\Models\BackendMenu;
use CMS\Components\Permission\PermissionPool;
use Favez\Mvc\Event\Arguments;
use User\Commands\PermissionPopulateCommand;

class Bootstrap extends \CMS\Components\Plugin\Bootstrap
{
    
    public function install()
    {
        $this->createMenu([
            'label'       => 'Themes',
            'url'         => '/config/themes',
            'icon'        => 'desktop',
            'permissions' => '',
            'position'    => 1,
            'parentID'    => BackendMenu::findOneBy(['label' => 'Settings'])->id
        ]);
        
        return true;
    }
    
    public function execute()
    {
        $this->subscribeEvent('vue.collector.run', [$this, 'onVueCollectorRun']);
    }
    
    public function onVueCollectorRun(Arguments $args)
    {
        /** @var \CMS\Components\Collector\Vue $collector */
        $collector = $args->get('collector');
        $collector->pushAlias(
            '@Themes',
            $this->getRelativePath() . 'Views/backend/src/',
            $this->getPath() . 'Views/backend/src'
        );
    }
    
}