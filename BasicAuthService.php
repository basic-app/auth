<?php
/**
 * @author basic-app <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Auth;

use Exception;

abstract class BasicAuthService implements AuthServiceInterface
{

    public $sessionKey;

    public function __construct(string $sessionKey = 'user')
    {
        $this->sessionKey = $sessionKey;
    }

    public function setId($id, bool $rememberMe = true, int $expires = 0)
    {
        if (!$rememberMe)
        {
            throw new Exception('The rememberMe argument is not implemented.');
        }

        if ($expires)
        {
            throw new Exception('The expires argument is not implemented.');
        }

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

    public function isGuest() : bool
    {
        return $this->getId() ? false : true;
    }

    public function isLogged() : bool
    {
        return $this->getId() ? true : false;
    }

}