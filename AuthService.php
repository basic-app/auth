<?php
/**
 * @author basic-app <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Auth;

class AuthService extends BasicAuthService
{

    public $cookie;

    public $token;

    public function __construct(string $sessionKey = 'user', $cookie = null, $token = null)
    {
        parent::__construct($sessionKey);

        $this->cookie = $cookie ?? new AuthCookie($this->sessionKey . '_token');

        $this->token = $cookie ?? new AuthToken($this->sessionKey . '_token');
    }

    public function setId($id, bool $rememberMe = true, int $expire = 0)
    {
        $this->unsetId();

        if (!$rememberMe)
        {
            $token = $this->token->generate();

            $this->token->set($token);

            $this->cookie->set($token, $expire);
        }

        return parent::setId($id);
    }

    public function getId()
    {
        $return = parent::getId();

        if ($return)
        {
            $token = $this->token->get();

            if ($token)
            {         
                if ($this->cookie->get() != $token)
                {
                    $this->unsetId();

                    return null;                
                }
            }
        }
        else
        {
            $this->unsetId();
        }

        return $return;
    }

    public function unsetId()
    {
        $this->cookie->delete();

        $this->token->remove();

        return parent::unsetId();
    }

}