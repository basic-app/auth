<?php
/**
 * @author basic-app <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Auth;

abstract class BasicAuthService
{

    public $sessionKey;

    public function __construct(string $sessionKey = 'user')
    {
        $this->sessionKey = $sessionKey;
    }

    public function setId($id)
    {
        return service('session')->set($this->sessionKey, $id);
    }

    public function getId()
    {
        return service('session')->get($this->sessionKey);
    }

    public function unsetId()
    {
        return service('session')->remove($this->sessionKey);
    }

}