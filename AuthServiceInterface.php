<?php
/**
 * @author basic-app <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Auth;

interface AuthServiceInterface
{

    public function setId($id, bool $rememberMe = true, int $expires = 0);

    public function getId();

    public function unsetId();

    public function isGuest() : bool;

    public function isLogged() : bool;

}