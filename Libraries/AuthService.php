<?php
/**
 * @author basic-app <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Auth\Libraries;

class AuthService extends \BasicApp\Auth\Libraries\BaseAuthService
{

    protected $_modelClass;

    protected $_user;

    public function __construct(string $modelClass, string $sessionKey = 'userId', ?RememberMe $rememberMe = null)
    {
        parent::__construct($sessionKey, $rememberMe);

        $this->_modelClass = $modelClass;
    }

    public function getModelClass()
    {
        return $this->_modelClass;
    }

    public function login($user, $rememberMe = true, &$error = null)
    {
        if (!AuthEvents::login($user, $error))
        {
            return false;
        }

        $model = model($this->_modelClass);

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
                $this->_user = model($this->_modelClass)->find($id);

                if (!$this->_user)
                {
                    $this->logout();
                }
            }
        }

        return $this->_user;
    }

}