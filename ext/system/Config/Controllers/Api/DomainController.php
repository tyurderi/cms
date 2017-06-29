<?php

namespace CMS\Controllers\Api;

use CMS\Components\Controller;
use CMS\Models\Domain\Domain;

class DomainController extends Controller
{
    
    public function listAction()
    {
        return $this->json()->success([
            'data' => $this->models()->newBuilder(Domain::class)->fetchArrayResult()
        ]);
    }
    
    public function getAction()
    {
        $id   = (int) $this->request()->getParam('id');
        $item = Domain::findByID($id);
        
        if ($item instanceof Domain)
        {
            return $this->json()->success([
                'data' => $item->get()
            ]);
        }
        
        return $this->json()->failure();
    }
    
    public function saveAction()
    {
        $id   = (int) $this->request()->getParam('id');
        $data = $this->request()->getParams();
        $item = Domain::findByID($id);
        
        if (!($item instanceof Domain))
        {
            $item = new Domain();
            $item->created = date('Y-m-d H:i:s');
            $item->changed = date('Y-m-d H:i:s');
        }
    
        $item->set([
            'active' => (int) $data['active'],
            'label'  => $data['label'],
            'host'   => $data['host'],
            'hosts'  => $data['hosts'],
            'secure' => (int) $data['secure']
        ]);
    
        $result = $item->validate();
    
        if (isSuccess($result))
        {
            $item->save();
        
            return $this->json()->success($item->get());
        }
    
        return $this->json()->failure($result);
    }
    
    public function removeAction()
    {
        $id   = (int) $this->request()->getParam('id');
        $item = Domain::findByID($id);
    
        if ($item instanceof Domain)
        {
            $item->delete();
            
            return $this->json()->success();
        }
    
        return $this->json()->failure();
    }
    
}