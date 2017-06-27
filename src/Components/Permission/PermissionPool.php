<?php

namespace CMS\Components\Permission;

use CMS\Models\Permission\Category;

class PermissionPool
{
    
    /**
     * @var \CMS\Models\Permission\Category
     */
    private $category;
    
    /**
     * @var array
     */
    private $permissions;
    
    public function __construct(Category $category)
    {
        $this->category    = $category;
        $this->permissions = [];
    }
    
    /**
     * Creates or fetch a permission by name and add it to the local pool.
     *
     * @param string $name
     * @param string $description
     * @param string $defaultValue
     */
    public function push($name, $description, $defaultValue = '0')
    {
        $permission = \CMS\Models\Permission\Permission::findOneBy(['name' => $name]);
        
        if (!($permission instanceof \CMS\Models\Permission\Permission))
        {
            $permission = new \CMS\Models\Permission\Permission();
            $permission->name       = $name;
            $permission->categoryID = $this->category->id;
        }
    
        $permission->description = $description;
        
        $this->permissions[$name] = [
            'model'        => $permission,
            'defaultValue' => $defaultValue
        ];
    }
    
    /**
     * Save all permissions in the pool.
     */
    public function save()
    {
        foreach ($this->permissions as $name => $item)
        {
            /** @var \CMS\Models\Permission\Permission $permission */
            $permission   = $item['model'];
            $defaultValue = $item['defaultValue'];
            
            $permission->save();
        }
    }
    
}