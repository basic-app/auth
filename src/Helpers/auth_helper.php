<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 */
use CodeIgniter\Events\Events;

if (!function_exists('user_id'))
{
    function user_id($set_user_id = null)
    {
        if ($set_user_id)
        {
            service('session')->set('user_id', $set_user_id);
        
            Events::on('login', $set_user_id);

            return $set_user_id;
        }

        $user_id = service('session')->get('user_id');

        if ($user_id && !$set_user_id && ($set_user_id !== null))
        {
            service('session')->remove('user_id');

            Events::on('logout', $user_id);

            return null;
        }

        return $user_id;
    }
}