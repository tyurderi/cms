<?php

namespace CMS\Models\Permission;

use Favez\Mvc\ORM\Entity;

class Category extends Entity
{

    public $id;

    public $label;

    public $description;

    public function initialize()
    {
        $this->hasMany(Permission::class, 'categoryID', 'id')
            ->setAlias('permissions');
    }

    public static function getSource()
    {
        return 'permission_category';
    }

}