<?php

namespace CMS\Models\Plugin;

use Favez\Mvc\ORM\Entity;

class Plugin extends Entity
{

    public $id;

    public $active;

    public $namespace;

    public $name;

    public $label;

    public $description;

    public $version;

    public $author;

    public $email;

    public $website;

    public $created;

    public $changed;

    public static function getSource()
    {
        return 'plugin';
    }

    public function initialize()
    {
        $this->hasMany(Dependency::class, 'pluginID', 'id')
            ->setName('dependencies');
    }

}