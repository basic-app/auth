<?php
/**
 * @author basic-app <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Auth\Interfaces;

interface AuthInterface extends BaseAuthInterface
{
    
    public function login($user, $rememberMe = true, &$error = null);

    public function logout();

    public function getUser(bool $refresh = false);

    public function getModel(bool $shared = true);

}