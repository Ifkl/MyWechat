<?php
require_once('WeChat.php');

class Common extends WeChat
{
    protected static $_accessToken;
    private $_appid = '';
    private $_secret = '';
    private $_token = '';

    /**
     * Common constructor.
     */
    public function __construct()
    {
        if (!$this->_appid || !$this->_secret || !$this->_token) {
            exit('请检查appid、secret、token');
        }
        parent::__construct($this->_appid, $this->_secret, $this->_token);
        self::$_accessToken = parent::isToken($this->_tokenPath);
        if (!self::$_accessToken) {
            self::$_accessToken = $this->getAccessToken();
            if ($this->_tokenStorage == 1) {
                file_put_contents($this->_tokenPath, self::$_accessToken);
            } elseif ($this->_tokenStorage == 2) {
                //将accesstoken写入redis
            }
        }
        if (!self::$_accessToken) {
            exit('AccessToken不存在');
        }
    }

    /**
     * @return bool
     */
    public static function isAccessToken()
    {
        return self::$_accessToken;
    }
}