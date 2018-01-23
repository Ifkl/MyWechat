<?php

class ResponseWx
{
    private $regestClass;

    public function __construct($regestClass)
    {
        $this->regestClass = $regestClass;
        if (!$this->regestClass instanceof Regest) {
            exit('没有Regest实例');
        }
    }

    /**
     *
     */
    public function responseToWechat()
    {
        $xml_str = $GLOBALS['HTTP_RAW_POST_DATA'];

        if (empty($xml_str)) {
            die('');
        }
        libxml_disable_entity_loader(true);//禁止xml实体解析，防止xml注入
        $request_xml = simplexml_load_string($xml_str, 'SimpleXMLElement', LIBXML_NOCDATA);//从字符串获取simpleXML对象

        switch ($request_xml->MsgType) {
            case 'event':
                $event = $request_xml->Event;
                $Types = array('location_select' => EVENT_LOCATION_SELECT, 'pic_weixin' => EVENT_PIC_WEIXIN, 'pic_photo_or_album' => EVENT_PIC_PHOTO_OR_ALBUM,
                    'pic_sysphoto' => EVENT_PIC_SYSPHOTO, 'scancode_waitmsg' => EVENT_SCANCODE_WAITMSG, 'scancode_push' => EVENT_SCANCODE_PUSH, 'SCAN' => EVENT_SCAN,
                    'CLICK' => EVENT_CLICK, 'VIEW' => EVENT_VIEW, 'LOCATION' => EVENT_LOCATION, 'subscribe' => EVENT_SUBSCRIBE, 'unsubscribe' => EVENT_UNSUBSCRIBE);
                $eventInfo = $this->getEventFuc($Types[$event]);
                call_user_func(array($eventInfo['class'], $eventInfo['name']), [$request_xml, $eventInfo['arg']]);
                break;
            case 'text':
            case 'image':
            case 'voice':
            case 'video':
            case 'shortvideo':
            case 'location':
            case 'link':
                $Types = array('text' => TEXT, 'image' => IMAGE, 'voice' => VOICE, 'video' => VIDEO, 'shortvideo' => SHORTVIDEO, 'location' => LOCATION, 'link' => LINK);
                $eventInfo = $this->getEventFuc($Types[$request_xml->MsgType]);
                call_user_func(array($eventInfo['class'], $eventInfo['name']), [$request_xml, $eventInfo['arg']]);
                break;
            default:
                die('');
                break;
        }
    }

    /**
     * [getEventFuc description]
     * @return [event] [事件]
     */
    private function getEventFuc($event)
    {
        return $this->regestClass->getFuc($event);
    }
}