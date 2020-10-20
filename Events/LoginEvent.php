<?php
/**
 * @author basic-app <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Auth\Events;

class LoginEvent
{

    public $model;

    public $user;

    public $result = true;

    public $error;

}