<?php

namespace CMS\Controllers\Api;

use CMS\Components\RESTController;
use CMS\Models\Permission\Value;
use CMS\Models\User\Group;

class GroupController extends RESTController
{
    
    protected $className = Group::class;
    
    /**
     * @param Group $group
     * @param array $data
     */
    protected function beforeSave($group, $data)
    {
        $group->set([
            'label' => $data['label']
        ]);
    }
    
    /**
     * @param Group $group
     * @param array $data
     */
    protected function afterSave($group, $data)
    {
        $this->savePermissions($group->id, $data['permissions']);
    }
    
    /**
     * @param Group $group
     */
    protected function beforeRemove($group)
    {
        $group->getRelated('permissionValues')->delete();
    }
    
    private function savePermissions($groupID, $permissions)
    {
        if (empty($groupID) || empty($permissions))
        {
            return false;
        }
        
        foreach ($permissions as $permissionID => $value)
        {
            $permissionValue = Value::findOneBy(['groupID' => $groupID, 'permissionID' => $permissionID]);
            
            if (!($permissionValue instanceof Value))
            {
                $permissionValue = new Value();
                $permissionValue->groupID      = $groupID;
                $permissionValue->permissionID = $permissionID;
            }
    
            $permissionValue->value = empty($value) ? '0' : '1';
            $permissionValue->save();
        }
        
        return true;
    }
    
}