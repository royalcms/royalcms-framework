<?php

namespace Royalcms\Component\Event;

/**
 * API事件基类
 *
 * @subpackage core
 */

abstract class Api extends Event
{
    /**
     * 构造函数
     */
    public function __construct()
    {

    }

    /**
     * 兼容event抽象方法
     *
     * @param unknown $param            
     */
    public function run($param)
    {
        if (method_exists($this, 'call')) {
            return call_user_func_array([$this, 'call'], $param);
        }

        return parent::run($param);
    }
    
}

// end