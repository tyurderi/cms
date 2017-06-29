<?php

namespace CMS\Controllers\Api;

use CMS\Components\RESTController;
use CMS\Models\Permission\Permission;

class PermissionController extends RESTController
{
    
    protected $className = Permission::class;
    
    /**
     * @param Permission $permission
     * @param array      $data
     */
    protected function beforeSave($permission, $data)
    {
        $permission->set([
            'name'        => $data['name'],
            'description' => $data['description'],
            'categoryID'  => $data['categoryID']
        ]);
    }
    
    /**
     * @param Permission $permission
     */
    protected function beforeRemove($permission)
    {
        $permission->getRelated('values')->delete();
    }

}