<?php
/**
 * Not Remember Me Cookie
 *
 * @author basic-app <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 *
 * Don't remember me function does not work correctly in Chrome (and other browsers) when:
 *
 *   1. On Startup = "Continue where you left off"
 *   2. Continue running background apps when Google Chrome is closed = "On"
 *
 * In this case the browser does not clean the remember me cookie if the remember me flag is not checked.
 */
namespace BasicApp\Auth;

use Config\App as AppConfig;

class TokenCookie
{

    public $name;

    public $domain;

    public $path;

    public $prefix;

    public $secure = false; // Whether to only send the cookie through HTTPS

    public $httpOnly = false; // Whether to hide the cookie from JavaScript

    public function __construct(string $name, $domain = null, $path = null, $prefix = null, $secure = null, $httpOnly = null)
    {
        $this->name = $name;

        $config = config(AppConfig::class);

        $this->domain = $domain ?? $config->cookieDomain;

        $this->path = $path ?? $config->cookiePath;

        $this->prefix = $prefix ?? $config->cookiePrefix;

        if ($secure !== null)
        {
            $this->secure = $secure;
        }

        if ($httpOnly !== null)
        {
            $this->httpOnly = $httpOnly;
        }
    }

    public function get()
    {
        helper(['cookie']);

        return get_cookie($this->name);
    }

    public function set(string $content, $expire = 0)
    {
        helper(['cookie']);   

        return set_cookie(
            $this->name,
            $content,
            $expire,
            $this->domain,
            $this->path,
            $this->prefix,
            $this->secure,
            $this->httpOnly
        );
    }

    public function delete()
    {
        if (!$this->get())
        {
            return;
        }

        helper(['cookie']);

        return delete_cookie(
            $this->name, 
            $this->domain, 
            $this->path, 
            $this->prefix
        );
    }

}