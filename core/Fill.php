<?php

class Fill
{
    private $templates;

    function __construct()
    {
        $this->templates = require_once('Template.php');
        if (empty($this->templates)) {
            exit('空的消息模板');
        }
    }

    /**
     * 填充文本模板
     * @param $to
     * @param $from
     * @param $content
     */
    public function fillText($to, $from, $content)
    {
        $response = sprintf($this->templates['text'], $to, $from, time(), $content);
        die($response);
    }

    /**
     * 填充图片模板
     * @param $to
     * @param $from
     * @param $mediaid
     */
    public function fillImage($to, $from, $mediaid)
    {
        $this->check($to, $from);
        if (!$mediaid) {
            exit('没有图片文件');
        }
        die(sprintf($this->templates['image'], $to, $from, time(), $mediaid));
    }

    private function check($to, $from)
    {
        if (!$to) {
            exit('接受者账号为空');
        }
        if (!$from) {
            exit('发送者账号为空');
        }
    }

    /**
     * 填充声音模板
     * @param $to
     * @param $from
     * @param $mediaid
     */
    public function fillVoice($to, $from, $mediaid)
    {
        $this->check($to, $from);
        if (!$mediaid) {
            exit('没有声音文件');
        }
        die(sprintf($this->templates['voice'], $to, $from, time(), $mediaid));
    }

    /**
     * 填充视频模板
     * @param $to
     * @param $from
     * @param $mediaid
     * @param $title
     * @param $des
     */
    public function fillVideo($to, $from, $mediaid, $title, $des)
    {
        $this->check($to, $from);
        if (!$mediaid) {
            exit('没有视频文件');
        }
        die(sprintf($this->templates['video'], $to, $from, time(), $mediaid, $title, $des));
    }

    /**
     * 填充音频模板
     * @param $to
     * @param $from
     * @param string $title
     * @param string $des
     * @param string $url
     * @param string $hurl
     * @param $thumbMediaId
     */
    public function fillMusic($to, $from, $title = '', $des = '', $url = '', $hurl = '', $thumbMediaId)
    {
        $this->check($to, $from);
        if (!$thumbMediaId) {
            exit('没有缩略图');
        }
        die(sprintf($this->templates['music'], $to, $from, time(), $title, $des, $url, $hurl, $thumbMediaId));
    }

    /**
     * @param $to
     * @param $from
     * @param array $item_list
     */
    public function fillImtext($to, $from, $item_list = array())
    {
        $this->check($to, $from);
        $item_str = '';
        foreach ($item_list as $item) {
            $item_str .= sprintf($this->templates['news_item'], $item['title'], $item['desc'], $item['picurl'], $item['url']);
        }
        die(sprintf($this->templates['news'], $to, $from, time(), count($item_list), $item_str));
    }
}