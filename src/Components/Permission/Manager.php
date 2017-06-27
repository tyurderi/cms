<?php

namespace CMS\Components\Permission;

use CMS\Models\Permission\Category;

class Manager
{
    
    /**
     * Creates a permission category within a callback to register permission for this category.
     *
     * @param string   $label
     * @param string   $description
     * @param callable $callback
     *
     * @return Category
     */
    public function create($label, $description, $callback)
    {
        $category = Category::findOneBy(['label' => $label]);
        
        if (!($category instanceof Category))
        {
            $category = new Category();
            $category->label = $label;
        }
    
        $category->description = $description;
        $category->save();
        
        if (is_callable($callback))
        {
            $pool = new PermissionPool($category);
            
            call_user_func_array(
                $callback,
                [
                    $pool
                ]
            );
            
            $pool->save();
        }
        
        return $category;
    }
    
}