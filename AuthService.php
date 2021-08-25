<?php
/**
 * @author basic-app <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Auth;

use CodeIgniter\Cookie\Cookie;

class AuthService extends BasicAuthService
{

    public function setId($id, bool $rememberMe = true, int $expires = 0)
    {
        $this->unsetId();

        if (!$rememberMe)
        {
            $token = $this->generateToken();

            $this->setToken($token);

            $this->setTokenCookie($token, $expires);
        }

        return parent::setId($id);
    }

    public function getId()
    {
        $return = parent::getId();

        if ($return)
        {
            $token = $this->getToken();

            if ($token)
            {         
                if ($this->getTokenCookie() != $token)
                {
                    $this->unsetId();

                    return null;                
                }
            }
        }
        else
        {
            $this->unsetId();
        }

        return $return;
    }

    public function unsetId()
    {
        $this->removeTokenCookie();

        $this->removeToken();

        return parent::unsetId();
    }

    public function generateToken()
    {
        return md5(time() . rand(0, PHP_INT_MAX)); 
    }

    public function getToken()
    {
        return service('session')->get($this->sessionKey . '_token');
    }

    public function removeToken()
    {
        return service('session')->remove($this->sessionKey . '_token');
    }

    public function setToken(string $token)
    {
        return service('session')->set($this->sessionKey . '_token', $token);
    }

    public function getTokenCookie()
    {
        return service('request')->getCookie($this->sessionKey . '_token');
    }

    public function setTokenCookie(string $content, $expires = 0)
    {
        $cookie = new Cookie($this->sessionKey . '_token', $content, [
            'expires' => $expires,
            'httponly' => false
        ]);

        cookies()->put($cookie)->dispatch();
    }

    public function removeTokenCookie()
    {
        if (cookies()->has($this->sessionKey . '_token'))
        {
            $this->setTokenCookie('');
        }
    }

}