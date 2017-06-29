<?php

namespace CMS\Controllers\Api;

use CMS\Components\RESTController;
use CMS\Models\Permission\Category;

class PermissionCategoryController extends RESTController
{
    
    protected $className = Category::class;
    
    /**
     * @param Category $category
     * @param array    $data
     */
    public function beforeSave($category, $data)
    {
        $category->set([
            'label'       => $data['label'],
            'description' => $data['description']
        ]);
    }
    
    /**
     * @param Category $category
     *
     * @return array|boolean
     */
    public function beforeRemove($category)
    {
        if ($category->getRelated('permissions')->count() > 0)
        {
            return [
                'success' => false,
                'message' => 'Unable to delete category since it is associated to certain permissions.'
            ];
        }
        
        return true;
    }
    
    public function listValuesAction()
    {
        $groupID = $this->request()->getParam('groupID');
        $query   = $this->db()->from('permission_value v')
            ->select(null)
            ->select('v.*')
            ->leftJoin('permission p ON p.id = v.permissionID')
            ->leftJoin('user_group g ON g.id = v.groupID')
            ->where('g.id', $groupID);
        
        $results = $query->fetchAll();
        
        return $this->json()->success([
            'data' => $results
        ]);
    }
    
}