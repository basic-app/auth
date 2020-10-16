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
    
    abstract public function login($user, $rememberMe = true, &$error = null);
    
    abstract public function logout();

    public function __construct(string $sessionKey = 'userId', ?RememberMe $rememberMe = null)
    {
        if (!$rememberMe)
        {
            $rememberMe = new RememberMe($sessionKey . 'rememberMe', $sessionKey . 'rememberMe');
        }

        $this->sessionKey = $sessionKey;
    
        $this->rememberMe = $rememberMe;
    }

    public function setUserId($userId, $rememberMe = true)
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
            if (!$this->rememberMe->validateToken())
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

    public function unsetUserId()
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