<?php

namespace CMS\Controllers\Api;

use Favez\Mvc\Controller;

class UserController extends Controller
{

    public function statusAction()
    {
        return self::json()->failure();
    }

}