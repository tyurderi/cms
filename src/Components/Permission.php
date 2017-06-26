<?php

namespace CMS\Components\Core;

use Favez\Mvc\App;

class Permission
{

    protected $values = [];

    public function check($name)
    {
        if ($this->has($name) == false)
        {
            $this->load($name);
        }

        return $this->get($name) == true;
    }

    public function get($name)
    {
        return $this->values[$name];
    }

    public function has($name)
    {
        return isset($this->values[$name]);
    }

    public function load($name)
    {
        $groupID = (int) App::auth()->user()->groupID;
        $query   = App::db()->from('permission_value apv');

        $query->leftJoin('permission ap ON ap.id = apv.permissionID')
            ->where('groupID = ?', $groupID)
            ->where('ap.name = ?', $name);

        $this->values[$name] = $query->fetch('value');
    }

    public function values()
    {
        return $this->values;
    }

}