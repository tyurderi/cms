<?php

namespace CMS\Models\User;

use Favez\Mvc\ORM\Entity;

class Session extends Entity
{

    public $id;

    public $userID;

    public $hash;

    public $expires;

    public static function getSource()
    {
        return 'user_session';
    }

    public function initialize()
    {
        $this->belongsTo('userID', User::class, 'id')
            ->setName('user');
    }

    public function expired()
    {
        return strtotime($this->expires) < time();
    }

}