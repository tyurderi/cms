<?php

namespace BackendMenu;

use BackendMenu\Models\BackendMenu;
use CMS\Components\Database\CapsuleManager;
use Favez\Mvc\Event\Arguments;
use Illuminate\Database\Schema\Blueprint;

class Bootstrap extends \CMS\Components\Plugin\Bootstrap
{
    
    public function install()
    {
        $manager = new CapsuleManager();
        $manager->create(BackendMenu::class);

        return true;
    }
    
    public function update($version)
    {
        switch ($version)
        {
            case '1.0.0':
                $manager = new CapsuleManager();
                $manager->schema()->table(BackendMenu::getSource(), function(Blueprint $table) {
                    $table->addColumn('string', 'icon', ['length' => 128]);
                });
            break;
        }
        
        return true;
    }
    
    public function uninstall()
    {
        $manager = new CapsuleManager();
        $manager->drop(BackendMenu::class);
        
        return true;
    }
    
    public function execute()
    {
        $this->subscribeEvent('core.plugin.pre_uninstall', [$this, 'onPluginUninstall']);
        $this->subscribeEvent('vue.collector.run', [$this, 'onVueCollectorRun']);

        $this->registerController('Api', 'Menu');
    }
    
    public function onPluginUninstall(Arguments $args)
    {
        /** @var \CMS\Components\Plugin\Instance $instance */
        $instance = $args->get('instance');
        $pluginID = $instance->getModel()->id;
        
        BackendMenu::repository()->findBy(['pluginID' => $pluginID])->delete();
    }
    
    public function onVueCollectorRun(Arguments $args)
    {
        /** @var \CMS\Components\Collector\Vue $collector */
        $collector = $args->get('collector');
        $collector->pushAlias(
            '@BackendMenu',
            $this->getRelativePath() . 'Views/backend/src/',
            $this->getPath() . 'Views/backend/src/'
        );
    }
    
}