<?php

namespace BackendMenu\Models;

use Favez\Mvc\ORM\Entity;

class BackendMenu extends Entity
{
    
    public $id;
    
    public $parentID;
    
    public $label;
    
    public $pluginID;
    
    public static function getSource()
    {
        return 'backend_menu';
    }
    
}