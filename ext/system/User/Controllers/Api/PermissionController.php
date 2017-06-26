<?php

namespace CMS\Controllers\Api;

use CMS\Components\Controller;
use CMS\Models\Permission\Category;
use CMS\Models\Permission\Permission;

class PermissionController extends Controller
{

    public function listAction()
    {
        return $this->json()->success([
            'data' => self::models()->newBuilder(Permission::class)->fetchArrayResult()
        ]);
    }
    
    public function getAction()
    {
        $id         = $this->request()->getParam('id');
        $permission = Permission::findByID((int) $id);
        
        if ($permission instanceof Permission)
        {
            return $this->json()->success(['data' => $permission->get()]);
        }
        
        return $this->json()->failure();
    }
    
    public function saveAction()
    {
        $data       = $this->request()->getParams();
        $permission = Permission::findByID((int) $this->request()->getParam('id'));
    
        if (!($permission instanceof Permission))
        {
            $permission = new Permission();
        }
    
        $permission->set([
            'name'        => $data['name'],
            'label'       => $data['label'],
            'description' => $data['description'],
            'categoryID'  => $data['categoryID']
        ]);
    
        $result = $permission->validate();
    
        if (isSuccess($result))
        {
            $permission->save();
        
            return $this->json()->success($permission->get());
        }
    
        return $this->json()->failure($result);
    }
    
    public function removeAction()
    {
        $permission = Permission::findByID((int) self::request()->getParam('id'));
    
        if ($permission instanceof Permission)
        {
            $permission->delete();
        
            return $this->json()->success();
        }
    
        return $this->json()->failure();
    }
    
    public function listCategoriesAction()
    {
        return $this->json()->success([
            'data' => self::models()->newBuilder(Category::class)->fetchArrayResult()
        ]);
    }
    
    public function saveCategoryAction()
    {
        $data     = $this->request()->getParams();
        $category = Category::findByID((int) $this->request()->getParam('id'));
        
        if (!($category instanceof Category))
        {
            $category = new Category();
        }
        
        $category->set([
            'label'       => $data['label'],
            'description' => $data['description']
        ]);
    
        $result = $category->validate();
    
        if (isSuccess($result))
        {
            $category->save();
        
            return $this->json()->success($category->get());
        }
    
        return $this->json()->failure($result);
    }
    
    public function removeCategoryAction()
    {
        $category = Category::findByID((int) self::request()->getParam('id'));
    
        if ($category instanceof Category)
        {
            $category->delete();
        
            return $this->json()->success();
        }
    
        return $this->json()->failure();
    }

}