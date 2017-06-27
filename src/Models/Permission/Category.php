<?php

namespace CMS\Models\Permission;

use Favez\Mvc\ORM\Entity;
use Validator\Validator;

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
    
    public function validate()
    {
        $this->setValidatorRules();
        
        $v = new Validator();
        $v->add('label', $this->label, 'required|unique_category_label', [
            'required'              => 'The label is required.',
            'unique_category_label' => 'The category label is already in use.'
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
        // Check if the passed label is not already in use
        Validator::addGlobalRule('unique_category_label', function($fields, $value, $params) {
            return Category::findBy(['label' => $value, 'NOT id' => $this->id])->count() === 0;
        });
    }

}