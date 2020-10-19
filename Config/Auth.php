<?php
/**
 * @author basic-app <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Auth\Config;

use App\Models\UserModel;

class Auth extends \CodeIgniter\Config\BaseConfig
{

    public $modelClass = UserModel::class;

}