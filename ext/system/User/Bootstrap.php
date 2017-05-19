<?php

namespace User;

use Favez\Mvc\Event\Arguments;

class Bootstrap extends \CMS\Components\Plugin\Bootstrap
{
    
    public function install()
    {
        $this->createMenu([
            'label' => 'Users',
            'url'   => '/users'
        ]);
        
        return true;
    }
    
    public function execute()
    {
        $this->subscribeEvent('vue.collector.run', [$this, 'onVueCollectorRun']);
        
        $this->registerController('Api', 'User');
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