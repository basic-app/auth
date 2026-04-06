<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 */
use CodeIgniter\Events\Events;
use CodeIgniter\Cookie\Cookie;

if (!function_exists('user_id'))
{
    function user_id($set_user_id = null, bool $rememberMe = true)
    {
        if ($set_user_id)
        {
            // logout old session
            user_id(false);

            // login
            service('session')->set('user_id', $set_user_id);

            // set remember flag
            if (!$rememberMe)
            {    
                $token = md5(time() . rand(0, PHP_INT_MAX));

                service('session')->set('user_id_token', $token);

                $cookie = new Cookie('user_id_token', $token, [
                    'expires' => 0,
                    'httponly' => false
                ]);
                
                service('response')->setCookie($cookie);
            }

            Events::on('login', $set_user_id);

            return $set_user_id;
        }

        $user_id = service('session')->get('user_id');

        if ($user_id && !$set_user_id && ($set_user_id !== null))
        {
            // logout
            if (cookies()->has('user_id_token'))
            {
                $cookie = new Cookie('user_id_token', '', [
                    'expires' => 0,
                    'httponly' => false
                ]);

                cookies()->put($cookie);
            }

            service('session')->remove('user_id');
            service('session')->remove('user_id_token');

            Events::on('logout', $user_id);

            return null;
        }

        // check remember flag

        $token = service('session')->get('user_id_token');

        if ($token)
        {         
            if (service('request')->getCookie('user_id_token') != $token)
            {
                user_id(false);

                return null;   
            }
        }

        return $user_id;
    }
}