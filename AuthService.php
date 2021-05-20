<?php
/**
 * @author basic-app <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Auth;

class AuthService extends BasicAuthService
{

    protected $tokenCookie;

    protected $token;

    public function __construct(string $sessionKey = 'user')
    {
        parent::__construct($sessionKey);

        $this->tokenCookie = new TokenCookie($this->sessionKey . '_token');

        $this->token = new Token($this->sessionKey . '_token');
    }

    public function setId($id, bool $rememberMe = true, int $expire = 0)
    {
        $this->unsetId();

        if (!$rememberMe)
        {
            $token = $this->token->generate();

            $this->token->set($token);

            $this->tokenCookie->set($token, $expire);
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
                if ($this->tokenCookie->get() != $token)
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
        $this->tokenCookie->delete();

        $this->token->remove();

        return parent::unsetId();
    }

}