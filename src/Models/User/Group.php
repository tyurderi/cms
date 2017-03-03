<?php

namespace CMS\Models\User;

use Favez\Mvc\ORM\Entity;

class Group extends Entity
{

    public $id;

    public $label;

    public static function getSource()
    {
        return 'user_group';
    }

    public function initialize()
    {
        $this->hasMany(User::class, 'groupID', 'id')
            ->setName('users');
    }

}