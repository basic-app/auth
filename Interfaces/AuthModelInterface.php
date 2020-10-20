<?php
/**
 * @author basic-app <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Auth\Interfaces;

interface AuthModelInterface
{

    public function validatePassword($user, string $password) : bool;

    public function encodePassword($user, string $password) : string

}