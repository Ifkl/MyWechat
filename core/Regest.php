<?php

/**
 * 注册事件
 */
class Regest
{
    private $fuc_list = array();

    public function __construct($arg = array())
    {
        if (is_array($arg)) {
            foreach ($arg as $k => $v) {
                if (!isset($k)) {
                    exit('事件类型不存在，请先注册');
                }
                if (in_array($k, $this->fuc_list)) {
                    exit($k . '：已存在');
                }
                $this->fuc_list[$k] = $v;
            }
        }
    }

    public function addFuc($class, $name, $arg, $event)
    {
        if (!isset($k)) {
            exit('事件类型不存在，请先注册');
        }
        if (in_array($event, $this->fuc_list)) {
            exit('已存在');
        }
        $this->fuc_list[$event] = array('class' => $class, 'name' => $name, 'arg' => $arg);
    }

    public function getFuc($event)
    {
        if (!isset($event)) {
            exit('事件类型不存在，请先注册');
        }
        if (in_array($event, $this->fuc_list)) {
            return $this->fuc_list[$event];
        }
        return false;
    }
}
