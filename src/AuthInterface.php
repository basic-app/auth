<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 */
namespace BasicApp\Auth;

interface AuthInterface
{

    public function login($user_id, bool $rememberMe = true, int $expires = 0);

    public function logout();
    
    public function user_id();

}