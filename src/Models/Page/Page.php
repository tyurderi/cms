<?php

namespace CMS\Models\Page;

use CMS\Models\Domain\Domain;
use Favez\Mvc\ORM\Entity;

class Page extends Entity
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
    
    public $domainID;
    
    public $parentID;
    
    public $label;
    
    public $type;
    
    public $created;
    
    public $changed;

    public $position;
    
    public function initialize()
    {
        $this->belongsTo('domainID', Domain::class, 'id')->setAlias('domain');
        
        $this->belongsTo('parentID', Page::class, 'id')->setAlias('parent');
        $this->hasMany(Page::class, 'parentID', 'id')->setAlias('children');
    }
    
    public static function getSource()
    {
        return 'page';
    }
    
}