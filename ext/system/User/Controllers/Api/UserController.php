<?php

namespace CMS\Controllers\Api;

use CMS\Components\Controller;
use CMS\Models\Domain\Domain;
use CMS\Models\User\User;
use Favez\ORM\Entity\Paginator;

class UserController extends Controller
{
    
    public function getAction()
    {
        $userID = self::request()->getParam('id');
        $user   = User::findByID($userID);
        
        if ($user instanceof User)
        {
            return $this->json()->success([
                'data' => $user->get()
            ]);
        }
        
        return $this->json()->failure();
    }
    
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
        
        self::json()->assign($paginator->paginate());
        
        return self::json()->success();
    }
    
    public function saveAction()
    {
        $data = self::request()->getParams();
        $user = User::findByID((int) self::request()->getParam('id'));
        
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
            
            return $this->json()->success($user->get());
        }
            
        return $this->json()->failure($result);
    }

    public function removeAction()
    {
        $user = User::findByID((int) self::request()->getParam('id'));

        if ($user instanceof User)
        {
            $user->getRelated('sessions')->delete();
            $user->delete();

            return $this->json()->success();
        }

        return $this->json()->failure();
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
                'domain'      => $this->getDomains()
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