<?php

namespace Themes\Models\Theme;

use Favez\Mvc\ORM\Entity;
use Illuminate\Database\Schema\Blueprint;

class Theme extends Entity
{
    
    public $id;
    
    public $module;
    
    public $name;
    
    public $label;
    
    public $description;
    
    public $created;
    
    public $changed;
    
    public static function getSource()
    {
        return 'theme';
    }
    
    public static function createSchema(Blueprint $table)
    {
        $table->increments('id')->unique();
        $table->string('module')->length(16);
        $table->string('name')->length(255);
        $table->string('label')->length(255);
        $table->text('description');
        $table->timestamp('created');
        $table->timestamp('changed');
    }
    
}