<?php
/**
 * @author Basic App Dev Team <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
use BasicApp\Auth\Config\Auth;

if (!function_exists('auth'))
{
    function auth()
    {
        return service('auth');
    }
}

if (!function_exists('user_id'))
{
    function user_id()
    {
        return auth()->getUserId();
    }
}