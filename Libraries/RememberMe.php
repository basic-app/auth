<?php
/**
 * CodeIgniter 4 Remember Me Cookie
 *
 * @author basic-app <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 *
 * Remember me feature not working correct in Chrome (and other browsers) when:
 *
 *   1. On Startup = "Continue where you left off"
 *   2. Continue running background apps when Google Chrome is closed = "On"
 *
 * In this case browser not clean remember me cookie when remember me flag is not checked.
 */
namespace BasicApp\Auth\Libraries;

use Config\App;
use Exception;

class RememberMe
{

    public $cookieName;

    public $sessionKey;

    public $secure = false; // Whether to only send the cookie through HTTPS

    public $httpOnly = false; // Whether to hide the cookie from JavaScript

    public $cookieDomain;

    public $cookiePath;

    public $cookiePrefix;

    public function __construct(string $sessionKey = 'userRememberMe', string $cookieName = 'userRememberMe')
    {
        helper(['cookie']);

        $this->cookieName = $cookieName;

        $this->sessionKey = $sessionKey;

        $config = config(App::class);

        if (!$config)
        {
            throw new Exception('App config not found.');
        }

        $this->cookieDomain = $config->cookieDomain;

        $this->cookiePath = $config->cookiePath;

        $this->cookiePrefix = $config->cookiePrefix;
    }

    public function generateToken()
    {
        return md5(time() . rand(0, PHP_INT_MAX)); 
    }

    public function validateToken()
    {
        $sessionToken = $this->getToken();

        if ($sessionToken)
        {
            $cookieToken = $this->getCookie();
        
            if ($cookieToken != $sessionToken)
            {
                return false;
            }
        }

        return true;
    }


    public function getCookie()
    {
        return get_cookie($this->cookieName);
    }

    public function setCookie(string $token)
    {
        return set_cookie(
            $this->cookieName,
            $token,
            0,
            $this->cookieDomain,
            $this->cookiePath,
            $this->cookiePrefix,
            $this->secure,
            $this->httpOnly
        );
    }

    public function deleteCookie()
    {
        return delete_cookie(
            $this->cookieName, 
            $this->cookieDomain, 
            $this->cookiePath, 
            $this->cookiePrefix
        );
    }

    public function getToken()
    {
        return service('session')->get($this->sessionKey);
    }

    public function setToken(string $token)
    {
        return service('session')->set($this->sessionKey, $token);
    }

    public function removeToken()
    {
        return service('session')->remove($this->sessionKey);
    }

}