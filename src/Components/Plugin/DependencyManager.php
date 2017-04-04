<?php

namespace CMS\Components\Plugin;

use CMS\Models\Plugin\Dependency;
use CMS\Models\Plugin\Plugin;
use Exception;
use Favez\Mvc\App;

class DependencyManager
{
    
    /**
     * Update the plugin dependencies in database.
     *
     * @param \CMS\Components\Plugin\Instance $instance
     */
    public function update(Instance $instance)
    {
        $info  = $instance->getInfo();
        $model = $instance->getModel();
        
        // Loop through all local plugin dependencies and update/write it in database.
        foreach ($info->getRequires() as $name => $version)
        {
            $dependency = Dependency::repository()->findOneBy(['pluginID' => $model->id, 'name' => $name]);
        
            if (!($dependency instanceof Dependency))
            {
                $dependency = new Dependency();
                $dependency->pluginID = $model->id;
                $dependency->name     = $name;
            }
        
            $dependency->version = $version;
            $dependency->save();
        }
        
        // Loop through all plugin dependencies in database and remove it when not exists locally.
        $dependencies = Dependency::repository()->findBy(['pluginID' => $model->id]);

        /** @var Dependency $dependency */
        foreach ($dependencies as $dependency)
        {
            foreach ($info->getRequires() as $name => $version)
            {
                if ($name === $dependency->name)
                {
                    continue 2;
                    break;
                }
            }
    
            $dependency->delete();
        }
    }
    
    /**
     * Load the depended and enabled plugins for the given plugin name.
     *
     * @param string $name
     *
     * @return array
     */
    public function getDependencies($name)
    {
        return App::db()->from('plugin p')
            ->select(null)->select('p.name')
            ->leftJoin('plugin_dependency pd ON pd.pluginID = p.id')
            ->where('p.active = 1')
            ->where('pd.name = ?', $name)
            ->fetchPairs(0, 'p.name');
    }
    
    /**
     * Checks whether the requirements of the plugin are met or not.
     *
     * @param \CMS\Components\Plugin\Instance $instance
     *
     * @return bool
     * @throws \Exception
     */
    public function checkRequirements(Instance $instance)
    {
        $requires = $instance->getInfo()->getRequires();
        
        foreach ($requires as $name => $version)
        {
            $plugin = Plugin::repository()->findOneBy(['name' => $name, 'active' => true]);
            
            if (!($plugin instanceof Plugin))
            {
                throw new Exception('Required plugin not found or installed: ' . $name);
            }
            
            if (!version_compare($version, $plugin->version, '<='))
            {
                throw new Exception(sprintf('Required plugin version (%s) does not match! (%s)', $version, $plugin->version));
            }
        }
        
        return true;
    }
    
}