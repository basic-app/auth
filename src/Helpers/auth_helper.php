<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 */
use CodeIgniter\Events\Events;
use CodeIgniter\Cookie\Cookie;

if (!function_exists('logout'))
{
    function logout($guard = 'user')
    {
        if (cookies()->has($guard . '_token'))
        {
            $cookie = new Cookie($guard . '_token', '', [
                'expires' => 0,
                'httponly' => false
            ]);

            service('response')->setCookie($cookie);
        }

        service('session')->remove($guard);
        service('session')->remove($guard . '_token');

        Events::on('logout', $user_id, $guard);
    }
}

if (!function_exists('login'))
{
    function login($user_id = null, bool $remember_me = true, $guard = 'user')
    {
        // logout old session
        logout($guard);

        // login
        service('session')->set($guard . '_id', $user_id);

        // set remember flag
        if (!$remember_me)
        {    
            $token = md5(time() . rand(0, PHP_INT_MAX));

            service('session')->set($guard . '_token', $token);

            $cookie = new Cookie($guard . '_token', $token, [
                'expires' => 0,
                'httponly' => false
            ]);
            
            service('response')->setCookie($cookie);
        }

        Events::on('login', $user_id, $guard);
    }
}

if (!function_exists('user_id'))
{
    function user_id($guard = 'user')
    {
        $user_id = service('session')->get($guard . '_id');
        
        if ($user_id)
        {
            // check remember flag
            $token = service('session')->get($guard . '_token');

            if ($token)
            {         
                if (service('request')->getCookie($guard . '_token') != $token)
                {
                    logout($guard);

                    return null;   
                }
            }

            return $user_id;
        }

        return null;
    }
}