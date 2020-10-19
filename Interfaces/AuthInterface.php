<?php
/**
 * @author basic-app <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Auth\Interfaces;

interface AuthInterface
{
    
    public function login($user, $rememberMe = true, &$error = null);

    public function setUserId($id, $rememberMe = true);

    public function getUserId();

    public function logout();

    public function unsetUserId();

    public function isLogged() : bool;

    public function isGuest() : bool;

    public function getUser();

}