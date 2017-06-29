<?php

namespace CMS\Models\Domain;

use CMS\Models\Page\Page;
use Favez\Mvc\ORM\Entity;
use Validator\Validator;

class Domain extends Entity
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
        $this->hasMany(Page::class, 'domainID', 'id')->setAlias('pages');
    }
    
    public static function getSource()
    {
        return 'domain';
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