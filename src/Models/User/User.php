<?php

namespace CMS\Models\User;

use Favez\Mvc\ORM\Entity;
use Validator\Validator;

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
    
    public function validate()
    {
        $this->setValidatorRules();
        
        $v = new Validator();
        $v->add('email', $this->email, 'required|email|unique_email', [
            'required'     => 'The email is required.',
            'email'        => 'Please enter a form-valid email.',
            'unique_email' => 'The email is already in use.'
        ]);
        
        $v->add('groupID', $this->groupID, 'group_exists', [
            'group_exists' => 'Please select an existing group.'
        ]);
        
        $v->validate();
        
        if (!$v->passes())
        {
            return [
                'success'  => false,
                'messages' => $v->errors()
            ];
        }
        
        return true;
    }
    
    protected function setValidatorRules()
    {
        // Check if the passed email is not already in use
        Validator::addGlobalRule('unique_email', function($fields, $value, $params) {
            return User::findBy(['email' => $value, 'NOT id' => $this->id])->count() === 0;
        });
        
        // Check if the passed groupID exists
        Validator::addGlobalRule('group_exists', function($fields, $value, $params) {
            return Group::findByID($value) instanceof Group;
        });
    }

}