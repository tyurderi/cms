<?php

namespace CMS\Controllers\Api;

use CMS\Components\RESTController;
use CMS\Models\Domain\Domain;
use CMS\Models\User\User;

class UserController extends RESTController
{
    
    protected $className = User::class;
    
    /**
     * @param User  $user
     * @param array $data
     */
    protected function onCreate($user, $data)
    {
        $user->created = date('Y-m-d H:i:s');
    }
    
    /**
     * @param User $user
     * @param array $data
     */
    protected function beforeSave($user, $data)
    {
        if (!empty($data['password']))
        {
            $user->passwordHash = self::auth()->hash($data['password']);
        }
    
        $user->set([
            'email'     => $data['email'],
            'firstname' => $data['firstname'],
            'lastname'  => $data['lastname'],
            'groupID'   => $data['groupID'],
            'changed'   => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * @param User $user
     */
    protected function beforeRemove($user)
    {
        $user->getRelated('sessions')->delete();
    }
    
    public function statusAction()
    {
        if (self::auth()->loggedIn())
        {
            return self::json()->success([
                'permissions' => $this->getPermissions(),
                'domains'     => $this->getDomains()
            ]);
        }
        
        return self::json()->failure();
    }
    
    public function loginAction()
    {
        $email     = self::request()->getParam('email');
        $password  = self::request()->getParam('password');
        $keepLogin = self::request()->getParam('keepLogin');
        
        if (self::auth()->login($email, $password, $keepLogin))
        {
            return self::json()->success([
                'permissions' => $this->getPermissions(),
                'domains'     => $this->getDomains()
            ]);
        }
        
        return self::json()->failure();
    }
    
    public function logoutAction()
    {
        self::auth()->clear();
        
        return self::json()->success();
    }
    
    private function getPermissions()
    {
        $groupID = self::auth()->user()->groupID;
        $query   = $this->db()->from('permission_value v')
            ->select(null)
            ->select('v.*, p.name')
            ->leftJoin('permission p ON p.id = v.permissionID')
            ->leftJoin('user_group g ON g.id = v.groupID')
            ->where('g.id', $groupID);
    
        return $query->fetchAll();
    }
    
    private function getDomains()
    {
        return $this->models()->newBuilder(Domain::class)->fetchArrayResult();
    }
    
}