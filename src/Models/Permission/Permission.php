<?php

namespace CMS\Models\Permission;

use Favez\Mvc\ORM\Entity;
use Validator\Validator;

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
    
    public function validate()
    {
        $this->setValidatorRules();
        
        $v = new Validator();
        $v->add('categoryID', $this->categoryID, 'required|permission_category_exists', [
            'required'                   => 'Please select a category!',
            'permission_category_exists' => 'The selected category does not exist!'
        ]);
        
        $v->add('name', $this->name, 'required|unique_permission_name', [
            'required'               => 'Please enter an unique name.',
            'unique_permission_name' => 'The name is already used by another permission.'
        ]);
        
        $v->add('label', $this->label, 'required', [
            'required' => 'The label is required.'
        ]);
        
        $v->add('description', $this->description, 'required', [
            'required' => 'The category should have a description.'
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
        // Check if the passed name is not already in use
        Validator::addGlobalRule('unique_permission_name', function($fields, $value, $params) {
            return Permission::findBy(['name' => $value, 'NOT id' => $this->id])->count() === 0;
        });
        
        // Check if the passed category exists
        Validator::addGlobalRule('permission_category_exists', function($fields, $value, $params) {
            return Category::findByID($value) instanceof Category;
        });
    }

}