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