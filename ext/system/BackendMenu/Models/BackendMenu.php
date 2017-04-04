<?php

namespace BackendMenu\Models;

use Favez\Mvc\ORM\Entity;
use Illuminate\Database\Schema\Blueprint;

class BackendMenu extends Entity
{
    
    public $id;
    
    public $parentID;
    
    public $position;
    
    public $label;
    
    public $url;
    
    public $pluginID;
    
    public static function getSource()
    {
        return 'backend_menu';
    }
    
    public static function createSchema(Blueprint $table)
    {
        $table->increments('id')->unique();
        $table->integer('parentID')->nullable();
        $table->integer('position')->default(0)->nullable();
        $table->string('label')->length(128);
        $table->string('url')->length(255);
        $table->integer('pluginID')->nullable();
    }
    
}