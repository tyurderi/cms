<?php

namespace CMS\Controllers\Api;

use CMS\Components\Controller;
use CMS\Models\User\Group;
use CMS\Models\User\User;
use Exception;
use Favez\ORM\Entity\Paginator;

class GroupController extends Controller
{
    
    
    public function listAction()
    {
        return self::json()->success([
            'data' =>  self::models()->newBuilder(Group::class)->fetchArrayResult()
        ]);
    }
    
}