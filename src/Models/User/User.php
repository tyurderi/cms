<?php

namespace CMS\Models\User;

use Favez\Mvc\ORM\Entity;

class User extends Entity
{

    public $id;

    public $email;

    public $passwordHash;

    public $firstname;

    public $lastname;

    public $changed;

    public $created;

    public $groupID;

    public static function getSource()
    {
        return 'user';
    }

    public function initialize()
    {
        $this->belongsTo('groupID', Group::class, 'id')
            ->setName('group');

        $this->hasMany(Session::class, 'userID', 'id')
            ->setName('sessions');
    }

}