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
        $manager->schema()->create('backend_menu', function(Blueprint $table) {
            $table->increments('id')->unique();
            $table->integer('parentID')->notNull();
            $table->string('label')->notNull()->length(128);
            $table->integer('pluginID');
        });

        return true;
    }
    
    public function uninstall()
    {
        $manager = new CapsuleManager();
        $manager->schema()->dropIfExists('backend_menu');
        
        return true;
    }
    
    public function execute()
    {
        $this->subscribeEvent('core.plugin.pre_uninstall', [$this, 'onPluginUninstall']);
    }
    
    public function onPluginUninstall(Arguments $args)
    {
        /** @var \CMS\Components\Plugin\Instance $instance */
        $instance = $args->get('instance');
        $pluginID = $instance->getModel()->id;
        
        BackendMenu::repository()->findBy(['pluginID' => $pluginID])->delete();
    }
    
}