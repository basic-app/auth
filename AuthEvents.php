<?php
/**
 * @author basic-app <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Auth;

use BasicApp\Auth\Events\LoginEvent;
use BasicApp\Auth\Events\LogoutEvent;

class AuthEvents extends \CodeIgniter\Events\Events
{

    const EVENT_LOGIN = 'ba:login';

    const EVENT_LOGOUT = 'ba:logout';

    public static function onLogin($callback)
    {
        static::on(static::EVENT_LOGIN, $callback);
    }

    public static function onLogout($callback)
    {
        static::on(static::EVENT_LOGOUT, $callback);
    }

    public static function login($user, &$error = null)
    {
        $event = new LoginEvent;

        $event->user = $user;

        static::trigger(static::EVENT_LOGIN, $event);

        $error = $event->error;

        return $event->result;
    }

    public static function logout($user)
    {
        $event = new LogoutEvent;

        $event->user = $user;

        static::trigger(static::EVENT_LOGIN, $event);
    }

}