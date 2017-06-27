<?php

namespace CMS\Components\Core;

use Favez\Mvc\App;

class Permission
{
    
    /**
     * Holds available permission values.
     *
     * @var array
     */
    protected $values = [];
    
    /**
     * Check if a permissions value is true.
     * Returns false if user is not logged in.
     *
     * @param string $name
     *
     * @return bool
     */
    public function check($name)
    {
        if ($this->has($name) === false)
        {
            $this->load($name);
        }

        return $this->get($name) === true;
    }
    
    /**
     * Get a permission by name, if loaded.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function get($name)
    {
        return $this->values[$name];
    }
    
    /**
     * Check if a permission was loaded.
     *
     * @param string $name
     *
     * @return bool
     */
    public function has($name)
    {
        return isset($this->values[$name]);
    }
    
    /**
     * Load a permissions value and cache the result.
     * If no group id specified it uses the group from the current logged in user.
     *
     * @param string  $name
     * @param integer $groupID
     */
    public function load($name, $groupID = -1)
    {
        if ($groupID === -1)
        {
            $groupID = $this->getGroupID();
        }
        
        $query = App::db()->from('permission_value apv')
            ->leftJoin('permission ap ON ap.id = apv.permissionID')
            ->where('groupID = ?', $groupID)
            ->where('ap.name = ?', $name);

        $this->values[$name] = (bool) $query->fetch('value');
    }
    
    /**
     * Returns all loaded permissions.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->values;
    }
    
    /**
     * Loads all available permission values for the given group.
     * If no group id specified it uses the group from the current logged in user.
     *
     * @param integer $groupID
     */
    public function loadAll($groupID = -1)
    {
        if ($groupID === -1)
        {
            $groupID = $this->getGroupID();
        }
        
        $query = App::db()->from('permission_value v')
            ->select(null)
            ->select('p.name, v.value')
            ->leftJoin('permission p ON p.id = v.permissionID')
            ->where('groupID = ?', $groupID);
        
        $result = $query->fetchAll();
        
        foreach ($result as $item)
        {
            $this->values[$item['name']] = (bool) $item['value'];
        }
    }
    
    /**
     * Get the group id for the current logged in user.
     * Returns -1 if user not logged in.
     *
     * @return int
     */
    private function getGroupID()
    {
        if (App::auth()->loggedIn() === false)
        {
            return -1;
        }
        
        return (int) App::auth()->user()->groupID;
    }

}