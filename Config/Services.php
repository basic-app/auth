<?php
/**
 * @author basic-app <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Auth\Config;

use Exception;
use CodeIgniter\Config\Services as CoreServices;
use BasicApp\Auth\AuthService;

class Services extends CoreServices
{

    public static function auth($getShared = true)
    {
        if ($getShared)
        {
            return static::getSharedInstance(__FUNCTION__);
        }

        $config = config(Auth::class);

        if (!$config)
        {
            throw new Exception(Auth::class . ' not found.');
        }

        return new AuthService($config->modelClass);
    }

}
