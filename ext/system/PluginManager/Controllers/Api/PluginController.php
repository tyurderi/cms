<?php

namespace CMS\Controllers\Api;

use CMS\Components\Controller;
use CMS\Components\Plugin\Manager;
use Exception;

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
                'id'         => $plugin->getModel()->id,
                'name'       => $plugin->getName(),
                'label'      => $plugin->getInfo()->getLabel(),
                'version'    => $plugin->getModel()->version,
                'created'    => $plugin->getModel()->created,
                'changed'    => $plugin->getModel()->changed,
                'active'     => (int)$plugin->getModel()->active,
                'namespace'  => $plugin->getModel()->namespace,
                'needUpdate' => version_compare($plugin->getInfo()->getVersion(), $plugin->getModel()->version, '>'),
                'newVersion' => $plugin->getInfo()->getVersion()
            ];
        }
        
        $this->json()->assign('data', $plugins);
        
        return $this->json()->success();
    }
    
    public function installAction()
    {
        $name    = $this->request()->getParam('name');
        $manager = new Manager();
        $result  = $manager->install($name);
        
        if (!isSuccess($result))
        {
            return $this->json()->assign($result)->failure();
        }
        
        return $this->json()->success();
    }
    
    public function uninstallAction()
    {
        $name    = $this->request()->getParam('name');
        $manager = new Manager();
        $result  = $manager->uninstall($name);
    
        if (!isSuccess($result))
        {
            return $this->json()->assign($result)->failure();
        }
    
        return $this->json()->success();
    }
    
    public function removeAction()
    {
        $name    = $this->request()->getParam('name');
        $manager = new Manager();
        $result  = $manager->remove($name);
    
        if (!isSuccess($result))
        {
            return $this->json()->assign($result)->failure();
        }
        
        return $this->json()->success();
    }
    
    public function updateAction()
    {
        $name    = $this->request()->getParam('name');
        $manager = new Manager();
        $result  = $manager->update($name);
    
        if (!isSuccess($result))
        {
            return $this->json()->assign($result)->failure();
        }
    
        return $this->json()->success();
    }
    
}