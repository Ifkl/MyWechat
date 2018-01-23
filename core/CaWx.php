<?php
require_once('Event.php');
require_once('ClassConfig.php');
require_once('Regest.php');
require_once('ResponseWx.php');

class CaWx
{
    private $_regestClass;
    private $_responseWxClass;

    public function __construct()
    {
        $this->_regestClass = new Regest(require_once('EventConfig.php'));
        $this->_responseWxClass = new ResponseWx($this->_regestClass);
    }

    public function response()
    {
        $this->_responseWxClass->responseToWechat();
    }

}