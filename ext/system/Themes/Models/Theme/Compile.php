<?php

namespace Themes\Models\Theme;

use Favez\Mvc\ORM\Entity;
use Illuminate\Database\Schema\Blueprint;

class Compile extends Entity
{
    
    public $id;
    
    public $themeID;
    
    public $created;
    
    public $duration;
    
    public static function getSource()
    {
        return 'theme_compile';
    }
    
    public static function createSchema(Blueprint $table)
    {
        $table->increments('id')->unique();
        $table->integer('themeID');
        $table->timestamp('created');
        $table->double('duration');
    }
    
}