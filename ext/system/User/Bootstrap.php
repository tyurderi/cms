<?php

namespace User;

use BackendMenu\Models\BackendMenu;
use CMS\Components\Permission\PermissionPool;
use Favez\Mvc\Event\Arguments;
use User\Commands\PermissionPopulateCommand;

class Bootstrap extends \CMS\Components\Plugin\Bootstrap
{
    
    public function install()
    {
        $menu = $this->createMenu([
            'label'       => 'Users',
            'url'         => '/users',
            'icon'        => 'user',
            'permissions' => 'user.list',
            'position'    => 1
        ]);
    
        $this->createMenu([
            'label'       => 'Groups',
            'url'         => '/users/groups',
            'icon'        => 'users',
            'parentID'    => $menu->id,
            'permissions' => 'user.group.list'
        ]);
    
        $menu = $this->createMenu([
            'label'       => 'Permissions',
            'url'         => '/users/permissions',
            'icon'        => 'file-text',
            'parentID'    => $menu->id,
            'permissions' => 'permission.management'
        ]);
        
        $this->createMenu([
            'label'       => 'Categories',
            'url'         => '/users/permissions/categories',
            'icon'        => 'sitemap',
            'parentID'    => $menu->id,
            'permissions' => 'permission.management'
        ]);
        
        $this->registerPermissions();
        
        return true;
    }
    
    public function update($oldVersion)
    {
        switch ($oldVersion)
        {
            case '1.0.0':
                $this->createMenu([
                    'label'       => 'Categories',
                    'url'         => '/users/permissions/categories',
                    'icon'        => 'sitemap',
                    'parentID'    => BackendMenu::findOneBy(['label' => 'Permissions'])->id,
                    'permissions' => 'permission.management'
                ]);
            break;
            case '1.0.1':
                $this->registerPermissions();
            break;
        }
        
        return true;
    }
    
    public function execute()
    {
        $this->subscribeEvent('vue.collector.run', [$this, 'onVueCollectorRun']);
        
        $this->registerController('Api', 'User');
        $this->registerController('Api', 'Group');
        $this->registerController('Api', 'Permission');
        $this->registerController('Api', 'PermissionCategory');

        $this->subscribeEvent('core.console_commands.collect', [$this, 'getCommands']);
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

    public function getCommands()
    {
        return [
            new PermissionPopulateCommand()
        ];
    }
    
    /**
     * This method register all required permissions within default values.
     * Already existing permissions and categories will be overwritten.
     */
    protected function registerPermissions()
    {
        $manager = new \CMS\Components\Permission\Manager();
        $manager->create('User', 'User related permissions', function(PermissionPool $pool) {
            $pool->push('user.list',   'Show available users', '0');
            $pool->push('user.create', 'Create a user',        '0');
            $pool->push('user.edit',   'Edit a user',          '0');
            $pool->push('user.remove', 'Remove a user',        '0');
        });
        
        $manager->create('User Groups', 'User group related permissions', function(PermissionPool $pool) {
            $pool->push('user.group.list',   'Show available user groups', '0');
            $pool->push('user.group.create', 'Create a user group',        '0');
            $pool->push('user.group.edit',   'Edit a user group',          '0');
            $pool->push('user.group.remove', 'Remove a user group',        '0');
        });
        
        $manager->create('Plugin', 'Plugin management related permissions', function(PermissionPool $pool) {
            $pool->push('plugin.list',      'Show available plugins', '0');
            $pool->push('plugin.install',   'Install plugin',         '0');
            $pool->push('plugin.update',    'Update plugin',          '0');
            $pool->push('plugin.uninstall', 'Uninstall plugin',       '0');
            $pool->push('plugin.remove',    'Remove plugin',          '0');
        });
        
        $manager->create('Permission', 'Permission management', function(PermissionPool $pool) {
            $pool->push('permission.management', 'Manage permissions', '0');
        });
    }
    
}