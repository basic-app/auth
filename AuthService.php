<?php
/**
 * @author basic-app <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Auth;

class AuthService implements AuthInterface
{

    public $sessionKey;

    protected $rememberMe;

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
            $this->rememberMe->unsetToken();

            $this->rememberMe->unsetCookie();
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
            $this->rememberMe->unsetToken();

            $this->rememberMe->unsetCookie();
        }

        return $return;
    }

    public function logout()
    {
        service('session')->remove($this->sessionKey);

        $this->rememberMe->unsetCookie();

        $this->rememberMe->unsetToken();
    }

}