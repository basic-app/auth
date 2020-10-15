<?php
/**
 * @author basic-app <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Auth;

interface AuthServiceInterface
{
    
    public function login($id, $rememberMe = true);

    public function getUserId();

    public function logout();

}