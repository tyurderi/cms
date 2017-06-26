<?php

namespace CMS\Models\Permission;

use Favez\Mvc\ORM\Entity;

class Permission extends Entity
{

    public $id;

    public $categoryID;

    public $name;

    public $label;

    public $description;

    public function initialize()
    {
        $this->hasMany(Value::class, 'permissionID', 'id')
            ->setAlias('values');

        $this->belongsTo('categoryID', Category::class, 'id')
            ->setAlias('category');
    }

    public static function getSource()
    {
        return 'permission';
    }

}