<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 */
if (!function_exists('user_id'))
{
    function user_id()
    {
        helper(['auth']);

        return auth_user_id('user');
    }
}

if (!function_exists('login'))
{
    function login($id, bool $rememberMe = true)
    {
        helper(['auth']);

        return auth_login($id, $rememberMe, 'user');
    }
}

if (!function_exists('logout'))
{
    function logout()
    {
        helper(['auth']);

        return auth_logout('user');
    }
}