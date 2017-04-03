<?php

namespace CMS\Models\Plugin;

use Favez\Mvc\ORM\Entity;

class Dependency extends Entity
{

    public $id;

    public $pluginID;

    public $name;

    public $version;

    public static function getSource()
    {
        return 'plugin_dependency';
    }

    public function initialize()
    {
        $this->belongsTo('pluginID', Plugin::class, 'id')
            ->setName('plugins');
    }

}