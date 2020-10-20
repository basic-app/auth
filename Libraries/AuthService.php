<?php
/**
 * @author basic-app <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Auth\Libraries;

use BasicApp\Auth\Interfaces\AuthInterface;
use BasicApp\Auth\AuthEvents;

class AuthService extends BaseAuthService implements AuthInterface
{

    protected $_modelClass;

    protected $_user;

    public function __construct(string $modelClass, string $sessionKey = 'userId', ?RememberMe $rememberMe = null)
    {
        parent::__construct($sessionKey, $rememberMe);

        $this->_modelClass = $modelClass;
    }

    public function getModel(bool $shared = true)
    {
        return model($this->_modelClass, $shared);
    }

    public function login($user, $rememberMe = true, &$error = null)
    {
        $model = $this->getModel();

        if (!AuthEvents::login($model, $user, $error))
        {
            return false;
        }
        
        $primaryKey = $model->primaryKey;

        if ($model->returnType == 'array')
        {
            return $this->setUserId($user[$primaryKey], $rememberMe);
        }
        
        return $this->setUserId($user->{$primaryKey}, $rememberMe);
    }

    public function logout()
    {
        AuthEvents::logout($this->getModel(), $this->getUser());

        $this->unsetUserId();
    }

    public function getUser()
    {
        if (!$this->_user)
        {
            $id = $this->getUserId();

            if ($id)
            {
                $this->_user = $this->getModel()->find($id);

                if (!$this->_user)
                {
                    $this->unsetUserId();
                }
            }
        }

        return $this->_user;
    }

}