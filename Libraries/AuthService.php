<?php
/**
 * @author basic-app <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Auth\Libraries;

use BasicApp\Auth\Interfaces\AuthInterface;

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
        if (!AuthEvents::login($user, $error))
        {
            return false;
        }

        $model = $this->getModel();

        $primaryKey = $model->primaryKey;

        if ($model->returnType == 'array')
        {
            return $this->setUserId($user[$primaryKey], $rememberMe);
        }
        
        return $this->setUserId($user->{$primaryKey}, $rememberMe);
    }

    public function logout()
    {
        AuthEvents::logout($this->getUser());

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
                    $this->logout();
                }
            }
        }

        return $this->_user;
    }

}