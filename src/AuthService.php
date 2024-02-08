<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 */
namespace BasicApp\Auth;

use CodeIgniter\Cookie\Cookie;
use CodeIgniter\Events\Events;

class AuthService implements AuthInterface
{

    public $sessionKey = 'user_id';

    public $loginTrigger = 'login';

    public $logoutTrigger = 'logout';

    public function __construct(array $params = [])
    {
        foreach($params as $key => $value)
        {
            $this->$key = $value;
        }
    }

    public function login($id, bool $rememberMe = true, int $expires = 0)
    {
        $this->logout(false);

        if (!$rememberMe)
        {
            $token = md5(time() . rand(0, PHP_INT_MAX));

            service('session')->set($this->sessionKey . '_token', $token);

            $cookie = new Cookie($this->sessionKey . '_token', $token, [
                'expires' => $expires,
                'httponly' => false
            ]);

            cookies()->put($cookie)->dispatch();
        }

        service('session')->set($this->sessionKey, $id);
    
        if ($this->loginTrigger)
        {
            Events::on($this->loginTrigger, $id);
        }
    }

    public function logout($trigger = true)
    {
        if (cookies()->has($this->sessionKey . '_token'))
        {
            $cookie = new Cookie($this->sessionKey . '_token', '', [
                'expires' => 0,
                'httponly' => false
            ]);

            cookies()->put($cookie)->dispatch();
        }

        service('session')->remove($this->sessionKey . '_token');

        service('session')->remove($this->sessionKey);

        if ($trigger && $this->logoutTrigger)
        {
            Events::trigger($this->logoutTrigger);
        }
    }

    public function user_id()
    {
        $return = service('session')->get($this->sessionKey);

        if ($return)
        {
            $token = service('session')->get($this->sessionKey . '_token');

            if ($token)
            {         
                if (service('request')->getCookie($this->sessionKey . '_token') != $token)
                {
                    $this->logout(false);

                    return null;   
                }
            }

            return $return;
        }
        
        $this->logout(false);

        return null;
    }

}