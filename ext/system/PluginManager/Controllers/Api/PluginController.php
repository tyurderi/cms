<?php

namespace CMS\Controllers\Api;

use CMS\Components\Controller;
use CMS\Components\Plugin\Manager;

class PluginController extends Controller
{
    
    public function listAction()
    {
        $manager = new Manager();
    
        $manager->synchronize();
    
        $plugins = $manager->list();
        
        foreach ($plugins as &$plugin)
        {
            $plugin = [
                'id'        => $plugin->getModel()->id,
                'name'      => $plugin->getName(),
                'label'     => $plugin->getInfo()->getLabel(),
                'version'   => $plugin->getInfo()->getVersion(),
                'created'   => $plugin->getModel()->created,
                'changed'   => $plugin->getModel()->changed,
                'active'    => $plugin->getModel()->active,
                'namespace' => $plugin->getModel()->namespace
            ];
        }
        
        $this->json()->assign('data', $plugins);
        
        return $this->json()->success();
    }
    
}