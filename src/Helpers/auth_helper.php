<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 */
use CodeIgniter\Events\Events;
use CodeIgniter\Cookie\Cookie;

if (!function_exists('user_id'))
{
    function user_id($set_user_id = null, bool $rememberMe = true, $guard = 'user')
    {
        if ($set_user_id)
        {
            // logout old session
            user_id(false);

            // login
            service('session')->set($guard . '_id', $set_user_id);

            // set remember flag
            if (!$rememberMe)
            {    
                $token = md5(time() . rand(0, PHP_INT_MAX));

                service('session')->set($guard . '_token', $token);

                $cookie = new Cookie($guard . '_token', $token, [
                    'expires' => 0,
                    'httponly' => false
                ]);
                
                service('response')->setCookie($cookie);
            }

            Events::on('login', $set_user_id, $guard);

            return $set_user_id;
        }

        $user_id = service('session')->get($guard . '_id');

        if ($user_id && !$set_user_id && ($set_user_id !== null))
        {
            // logout
            if (cookies()->has($guard . '_token'))
            {
                $cookie = new Cookie($guard . '_token', '', [
                    'expires' => 0,
                    'httponly' => false
                ]);

                cookies()->put($cookie);
            }

            service('session')->remove($guard);
            service('session')->remove($guard . '_token');

            Events::on('logout', $user_id, $guard);

            return null;
        }

        // check remember flag

        $token = service('session')->get($guard . '_token');

        if ($token)
        {         
            if (service('request')->getCookie($guard . '_token') != $token)
            {
                user_id(false);

                return null;   
            }
        }

        return $user_id;
    }
}