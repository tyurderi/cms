<?php

namespace CMS\Models\User;

use CMS\Models\Permission\Value;
use Favez\Mvc\ORM\Entity;
use Validator\Validator;

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
            ->setAlias('users');
        
        $this->hasMany(Value::class, 'groupID', 'id')
            ->setAlias('permissionValues');
    }

    public function validate()
    {
        $this->setValidatorRules();

        $v = new Validator();
        $v->add('label', $this->label, 'required|unique_group_label', [
            'required'           => 'The label is required.',
            'unique_group_label' => 'The group label is already in use.'
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
        // Check if the passed label is not already in use
        Validator::addGlobalRule('unique_group_label', function($fields, $value, $params) {
            return Group::findBy(['label' => $value, 'NOT id' => $this->id])->count() === 0;
        });
    }

}