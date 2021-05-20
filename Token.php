<?php
/**
 * @author basic-app <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Auth;

class Token
{

    public $sessionKey;

    public function __construct(string $sessionKey)
    {
        $this->sessionKey = $sessionKey;
    }

    public function generate()
    {
        return md5(time() . rand(0, PHP_INT_MAX)); 
    }

    public function get()
    {
        return service('session')->get($this->sessionKey);
    }

    public function set(string $token)
    {
        return service('session')->set($this->sessionKey, $token);
    }

    public function remove()
    {
        return service('session')->remove($this->sessionKey);
    }

}