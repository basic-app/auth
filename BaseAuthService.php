<?php
/**
 * @author basic-app <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Auth;

abstract class BaseAuthService implements AuthInterface
{

    public $sessionKey;

    protected $rememberMe;

    abstract public function getUser();

    public function __construct(RememberMe $rememberMe, string $sessionKey = 'userId')
    {
        $this->sessionKey = $sessionKey;
    
        $this->rememberMe = $rememberMe;
    }

    public function login($userId, $rememberMe = true)
    {
        service('session')->set($this->sessionKey, $userId);

        if ($rememberMe)
        {
            $this->rememberMe->removeToken();

            $this->rememberMe->deleteCookie();
        }
        else
        {
            $token = $this->rememberMe->generateToken();

            $this->rememberMe->setToken($token);

            $this->rememberMe->setCookie($token);
        }
    }

    public function getUserId()
    {
        $return = service('session')->get($this->sessionKey);

        if ($return)
        {
            if (!$this->validateToken())
            {
                $this->logout();

                return null;
            }
        }
        else
        {
            $this->rememberMe->removeToken();

            $this->rememberMe->deleteCookie();
        }

        return $return;
    }

    public function logout()
    {
        service('session')->remove($this->sessionKey);

        $this->rememberMe->deleteCookie();

        $this->rememberMe->removeToken();
    }

    public function isGuest() : bool
    {
        return $this->getUser() ? false : true;
    }

    public function isLogged() : bool
    {
        return !$this->isGuest();
    }    

}