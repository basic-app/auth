<?php
/**
 * @author basic-app <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Auth\Interfaces;

interface BaseAuthInterface
{

    public function setUserId($id, $rememberMe = true);

    public function getUserId();

    public function unsetUserId();

    public function isLogged() : bool;

    public function isGuest() : bool;    

}