<?php

namespace Pages;

use Favez\Mvc\Event\Arguments;

class Bootstrap extends \CMS\Components\Plugin\Bootstrap
{
    
    public function install()
    {
        $this->createMenu([
            'position'    => '0',
            'label'       => 'Pages',
            'url'         => '/pages',
            'icon'        => 'file-text-o',
            'permissions' => ''
        ]);
        
        return true;
    }
    
    public function execute()
    {
        $this->registerController('Api', 'Page');
        
        $this->subscribeEvent('vue.collector.run', [$this, 'onVueCollectorRun']);
    }
    
    public function onVueCollectorRun(Arguments $args)
    {
        /** @var \CMS\Components\Collector\Vue $collector */
        $collector = $args->get('collector');
        $collector->pushAlias(
            '@Pages',
            $this->getRelativePath() . 'Views/backend/src/',
            $this->getPath() . 'Views/backend/src'
        );
    }
    
}