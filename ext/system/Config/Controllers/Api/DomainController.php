<?php

namespace CMS\Controllers\Api;

use CMS\Components\RESTController;
use CMS\Models\Domain\Domain;

class DomainController extends RESTController
{
    
    protected $className = Domain::class;
    
    /**
     * @param Domain $domain
     * @param array  $data
     */
    public function onCreate($domain, $data)
    {
        $domain->created = date('Y-m-d H:i:s');
        $domain->changed = date('Y-m-d H:i:s');
    }
    
    /**
     * @param Domain $domain
     * @param array  $data
     */
    public function beforeSave($domain, $data)
    {
        $domain->set([
            'active' => (int) $data['active'],
            'label'  => $data['label'],
            'host'   => $data['host'],
            'hosts'  => $data['hosts'],
            'secure' => (int) $data['secure']
        ]);
    }
    
}