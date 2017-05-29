<?php

namespace CMS\Models\Site;

use Favez\Mvc\ORM\Entity;

class Site extends Entity
{
    
    public $id;
    
    public $label;
    
    public $host;
    
    public $hosts;
    
    public $ssl;
    
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
    
}