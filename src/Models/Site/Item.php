<?php

namespace CMS\Models\Site;

use Favez\Mvc\ORM\Entity;

class Item extends Entity
{
    
    /**
     * This item is used as a content-page within a custom url.
     */
    const TYPE_ITEM = 1;
    
    /**
     * This item is just a redirect-page within a custom url.
     */
    const TYPE_URL  = 2;
    
    /**
     * This item acts like a category and usually have no parent item but is associated to a site.
     */
    const TYPE_SECTION = 3;
    
    public $id;
    
    public $siteID;
    
    public $parentID;
    
    public $label;
    
    public $type;
    
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