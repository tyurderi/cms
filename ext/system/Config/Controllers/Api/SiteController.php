<?php

namespace CMS\Controllers\Api;

use CMS\Components\Controller;
use CMS\Models\Site\Site;

class SiteController extends Controller
{
    
    public function listAction()
    {
        return $this->json()->success([
            'data' => $this->models()->newBuilder(Site::class)->fetchArrayResult()
        ]);
    }
    
    public function getAction()
    {
        $siteID = $this->request()->getParam('id');
        $site   = Site::findByID((int) $siteID);
        
        if ($site instanceof Site)
        {
            return $this->json()->success([
                'data' => $site->get()
            ]);
        }
        
        return $this->json()->failure();
    }
    
    public function saveAction()
    {
        $siteID = $this->request()->getParam('id');
        $data   = $this->request()->getParams();
        $site   = Site::findByID($siteID);
        
        if (!($site instanceof Site))
        {
            $site = new Site();
            $site->created = date('Y-m-d H:i:s');
            $site->changed = date('Y-m-d H:i:s');
        }
        
        $site->set([
            'active' => (int) $data['active'],
            'label'  => $data['label'],
            'host'   => $data['host'],
            'hosts'  => $data['hosts'],
            'secure' => (int) $data['secure']
        ]);
    
        $result = $site->validate();
    
        if (isSuccess($result))
        {
            $site->save();
        
            return $this->json()->success($site->get());
        }
    
        return $this->json()->failure($result);
    }
    
    public function removeAction()
    {
        $siteID = $this->request()->getParam('id');
        $site   = Site::findByID((int) $siteID);
    
        if ($site instanceof Site)
        {
            $site->delete();
            
            return $this->json()->success();
        }
    
        return $this->json()->failure();
    }
    
}