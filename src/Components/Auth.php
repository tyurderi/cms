<?php

namespace CMS\Components;

use CMS\Models\User\Session;
use CMS\Models\User\User;
use Favez\Mvc\DI\Injectable;
use const PASSWORD_BCRYPT;

class Auth
{
    use Injectable;

    const EXPIRE_TIME = 2592000;

    const COOKIE_KEY  = 'cms.sessionID';

    const SESSION_KEY = 'cms.sessionID';

    /**
     * @var User|null|integer
     */
    protected $user    = -1;

    /**
     * @var Session
     */
    protected $session = null;

    /**
     * This method is used to login a user by email and password.
     *
     * If the users password hash is empty you can login without password. This only happens when the cms got fresh
     * installed on the system and the user is noticed every time until the password gets changed.
     *
     * It also supports "keepSession" that will keep the user logged in for at least 30 days.
     *
     * @param string $email
     * @param string $password
     * @param bool   $keepLogin
     *
     * @return bool
     */
    public function login($email, $password = '', $keepLogin = false)
    {
        $user = User::repository()->findOneBy(['email' => $email]);

        if ($user instanceof User &&
            (empty($user->passwordHash) || password_verify($password, $user->passwordHash)))
        {
            $sessionID = $this->generateSessionID($user);

            self::session()->{self::SESSION_KEY} = $sessionID;

            $session = new Session();
            $session->userID  = (int) $user->id;
            $session->hash    = $sessionID;
            $session->expires = date('Y-m-d H:i:s', time() + 3600);

            if ($keepLogin)
            {
                $expireTimestamp  = time() + self::EXPIRE_TIME;
                $session->expires = date('Y-m-d H:i:s', $expireTimestamp);

                self::cookies()->set(self::COOKIE_KEY, $sessionID, $expireTimestamp, '/');
            }

            $session->save();

            $this->user     = $user;
            $this->loggedIn = true;

            return true;
        }

        return false;
    }
    
    /**
     * Generates a password hash.
     *
     * @param string $password
     * @return string
     */
    public function hash($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Return if the user is logged in or not.
     *
     * @return bool
     */
    public function loggedIn()
    {
        return $this->user() !== null;
    }

    /**
     * Log the user out if the user is logged in.
     *
     * @return bool
     */
    public function clear()
    {
        if ($this->loggedIn())
        {
            Session::repository()->findBy(['userID' => $this->userID()])->delete();

            self::cookies()->reset(self::COOKIE_KEY);
            self::session()->delete(self::SESSION_KEY);

            return true;
        }

        return false;
    }

    /**
     * Returns the current user id, if logged in. Otherwise it returns null.
     *
     * @return integer|null
     */
    public function userID()
    {
        return $this->user() ? (int) $this->user()->id : null;
    }

    /**
     * Returns the User-instance of the currently logged in user and caches the result for duplicate calls.
     *
     * @return User|null
     */
    public function user()
    {
        if ($this->user === -1)
        {
            $this->session = Session::repository()->findOneBy([
                'hash'=> $this->getSessionID(),
                'expires > NOW()'
            ]);

            if ($this->session instanceof Session)
            {
                $this->user = User::repository()->findByID($this->session->userID);
            }
            else
            {
                $this->user = null;
            }
        }

        return $this->user;
    }

    /**
     * Updates the user session in database.
     *
     * The expire time is selected by existing session cookie.
     *
     * @return bool
     */
    public function update()
    {
        if ($this->session instanceof Session)
        {
            $expireTime             = self::cookies()->get(self::COOKIE_KEY, null) !== null ? self::EXPIRE_TIME : 3600;
            $this->session->expires = date('Y-m-d H:i:s', time() + $expireTime);
            $this->session->save();

            return true;
        }

        return false;
    }

    /**
     * Returns the current sessionID and optionally set it by the session cookie, if any.
     *
     * @return null
     */
    protected function getSessionID()
    {
        if ($sessionID = self::cookies()->get(self::COOKIE_KEY))
        {
            self::session()->set(self::SESSION_KEY, $sessionID);
        }

        return self::session()->get(self::SESSION_KEY);
    }

    /**
     * Generates the sessionID with some information of the user included.
     *
     * @param User $user
     * @return string
     */
    protected function generateSessionID(User $user)
    {
        return md5(uniqid(self::SESSION_KEY) . $user->id . $user->groupID);
    }

}