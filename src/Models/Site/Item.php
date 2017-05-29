<?php

namespace CMS\Models\Site;

use Favez\Mvc\ORM\Entity;

class Item extends Entity
{
    
    public $id;
    
    public $siteID;
    
    public $parentID;
    
    public $label;
    
    public $created;
    
    public $changed;
    
    public function initialize()
    {
        $this->belongsTo('siteID', Site::class, 'id')->setAlias('site');
        
        $this->belongsTo('parentID', Item::class, 'id')->setAlias('parent');
        $this->hasMany(Item::class, 'parentID', 'id')->setAlias('children');
    }
    
    public static function getSource()
    {
        return 'site_item';
    }
    
}