<?php
require_once('Http.php');

/**
 * 微信公众平台操作类
 */
class WeChat
{

    const QRCODE_TYPE_TEMP = 1;
    const QRCODE_TYPE_LIMIT = 2;
    const QRCODE_TYPE_LIMIT_STR = 3;
    protected $_tokenExpireTime = 7200;
    protected $_tokenStorage = 1;
    protected $_tokenPath = './token_file';
    protected $_httpClass;

    //表示QRCode的类型
    private $_appid;
    private $_appsecret;
    private $_token;

    public function __construct($id, $secret, $token)
    {
        $this->_appid = $id;
        $this->_appsecret = $secret;
        $this->_token = $token;
        $this->_httpClass = new Http();
    }

    /**
     * 设置accesstoken存贮方式
     * type 1 文件 2 redis
     */
    public function setTokenStorageType($type)
    {
        $this->_tokenStorage = $type;
    }

    /**
     * [getQRCode 二维码]
     * @param  int|string $content qrcode内容标识
     * @param  [type]  $file    存储为文件的地址，如果为NULL表示直接输出
     * @param  integer $type 类型
     * @param  integer $expire 如果是临时，表示其有效期
     * @return [type]           [description]
     */
    public function getQRCode($content, $file = NULL, $type = 2, $expire = 604800)
    {
        // 获取ticket
        $ticket = $this->getQRCodeTicket($content, $type = 2, $expire = 604800);
        $url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=$ticket";
        $result = $this->_httpClass->requestGet($url);//此时result就是图像内容
        if ($file) {
            file_put_contents($file, $result);
        } else {
            header('Content-Type: image/jpeg');
            echo $result;
        }
    }

    /**
     * [getQRCodeTicket description]
     * @param $content 内容
     * @param $type qr码类型
     * @param $expire 有效期，如果是临时的类型则需要该参数
     * @return string ticket
     */
    private function getQRCodeTicket($content, $type = 2, $expire = 604800)
    {
        $access_token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
        $type_list = array(
            self::QRCODE_TYPE_TEMP => 'QR_SCENE',
            self::QRCODE_TYPE_LIMIT => 'QR_LIMIT_SCENE',
            self::QRCODE_TYPE_LIMIT_STR => 'QR_LIMIT_STR_SCENE',
        );
        $action_name = $type_list[$type];
        switch ($type) {
            case self::QRCODE_TYPE_TEMP:
                $data_arr['expire_seconds'] = $expire;
                $data_arr['action_name'] = $action_name;
                $data_arr['action_info']['scene']['scene_id'] = $content;
                break;
            case self::QRCODE_TYPE_LIMIT:
            case self::QRCODE_TYPE_LIMIT_STR:
                $data_arr['action_name'] = $action_name;
                $data_arr['action_info']['scene']['scene_id'] = $content;
                break;
        }
        $data = json_encode($data_arr);
        $result = $this->_httpClass->requestPost($url, $data);
        if (!$result) {
            return false;
        }
        //处理响应数据
        $result_obj = json_decode($result);
        return $result_obj->ticket;
    }

    /**
     * 获取 access_tonken
     */
    public function getAccessToken()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->_appid}&secret={$this->_appsecret}";
        $result = $this->_httpClass->requestGet($url);
        if (!$result) {
            return false;
        }
        $result_obj = json_decode($result);
        return $result_obj->access_token;
    }

    /**
     * 用于第一次验证URL合法性
     */
    protected function firstValid()
    {
        // 检验签名的合法性
        if ($this->checkSignature()) {
            echo $_GET['echostr'];
        }
    }

    /**
     * 验证签名
     * @return bool [description]
     */
    private function checkSignature()
    {
        $signature = $_GET['signature'];
        $timestamp = $_GET['timestamp'];
        $nonce = $_GET['nonce'];
        // 将时间戳，随机字符串，token按照字母顺序排序并连接
        $tmp_arr = array($this->_token, $timestamp, $nonce);
        sort($tmp_arr, SORT_STRING);// 字典顺序
        $tmp_str = implode($tmp_arr);//连接
        $tmp_str = sha1($tmp_str);// sha1签名

        if ($signature == $tmp_str) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 是否存在accesstoken
     */
    protected function isToken($token_file)
    {
        $life_time = $this->_tokenExpireTime;
        if ($this->_tokenStorage == 1) {
            if (!$token_file) {
                exit('路径不正确');
            }
            if (file_exists($token_file) && time() - filemtime($token_file) < $life_time) {
                return file_get_contents($token_file);
            }
        } elseif ($this->_tokenStorage == 2) {
            # 判断redis中accesstoken是否存在
            $redis_token = '';
            //此处读取redis代码
            //……………………
            if ($redis_token) {
                return $redis_token;
            }
        }
        return false;
    }


}