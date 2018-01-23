<?php
require_once('Common.php');

class Menu extends Common
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 创建菜单
     */
    public function createMenu()
    {
        $menu = file_get_contents(BASE_PATH . '/wechat/json/click.json');
        if (empty($menu)) {
            exit('空的菜单');
        }
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . parent::$_accessToken;
        $result = $this->_httpClass->requestPost($url, $menu);
    }

    /**
     * 菜单删除
     */
    public function deleteMenu()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=' . parent::$_accessToken;
        $result = $this->_httpClass->requestGet($url);
    }

    /**
     * 菜单查询
     */
    public function getMune()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token=' . parent::$_accessToken;
        $result = $this->_httpClass->requestGet($url);
    }
    /**
     *
     */

}