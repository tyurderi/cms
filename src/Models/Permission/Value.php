<?php

namespace CMS\Models\Permission;

use CMS\Models\User\Group;
use Favez\Mvc\ORM\Entity;

class Value extends Entity
{

    public $id;

    public $groupID;

    public $permissionID;

    public $value;

    public function initialize()
    {
        $this->belongsTo('permissionID', Permission::class, 'id')
            ->setAlias('permission');

        $this->belongsTo('groupID', Group::class, 'id')
            ->setAlias('group');
    }

    public static function getSource()
    {
        return 'permission_value';
    }

}