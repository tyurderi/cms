<?php

namespace CMS\Controllers\Api;

use CMS\Components\Controller;

class UserController extends Controller
{

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