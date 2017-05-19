<?php

namespace CMS\Controllers\Api;

use CMS\Components\Controller;
use CMS\Models\User\Group;
use CMS\Models\User\User;
use Favez\ORM\Entity\Paginator;

class UserController extends Controller
{
    
    public function listAction()
    {
        $page      = self::request()->getParam('page', 1);
        $limit     = self::request()->getParam('limit', 15);
        $paginator = self::models()->newPaginator([
            'mode'    => Paginator::MODE_ARRAY,
            'builder' => self::models()->newBuilder(User::class),
            'limit'   => $limit,
            'page'    => $page
        ]);
        
        $groups = self::models()->newBuilder(Group::class)->fetchArrayResult();
        
        self::json()->assign($paginator->paginate());
        self::json()->assign('groups', $groups);
        
        return self::json()->success();
    }
    
    public function saveAction()
    {
        $data = self::request()->getParams();
        $user = User::findByID(self::request()->getParam('id'));
        
        if (!($user instanceof User))
        {
            $user = new User();
            $user->created = date('Y-m-d H:i:s');
        }
        
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
        
        $result = $user->validate();
        
        if (isSuccess($result))
        {
            $user->save();
            
            return $this->json()->success();
        }
            
        return $this->json()->failure($result);
    }
    
    public function statusAction()
    {
        if (self::auth()->loggedIn())
        {
            return self::json()->success();
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
            return self::json()->success();
        }
        
        return self::json()->failure();
    }
    
    public function logoutAction()
    {
        self::auth()->clear();
        
        return self::json()->success();
    }
    
}