<?php

namespace CMS\Models\Site;

use Favez\Mvc\ORM\Entity;
use Validator\Validator;

class Site extends Entity
{
    
    public $id;
    
    public $active;
    
    public $label;
    
    public $host;
    
    public $hosts;
    
    public $secure;
    
    public $created;
    
    public $changed;
    
    public function initialize()
    {
        $this->hasMany(Item::class, 'siteID', 'id')->setAlias('items');
    }
    
    public static function getSource()
    {
        return 'site';
    }
    
    public function validate()
    {
        $v = new Validator();
        
        $v->add('label', $this->label, 'required', [
            'required' => 'A site label is required.'
        ]);
        
        $v->add('host', $this->host, 'required', [
            'required' => 'A site host is required.'
        ]);
    
        $v->validate();
    
        if (!$v->passes())
        {
            return [
                'success'  => false,
                'messages' => $v->errors()
            ];
        }
    
        return true;
    }
    
}