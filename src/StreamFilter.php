<?php

namespace fize\io;

class StreamFilter
{

    /**
     * 将后置过滤器附加到流
     *
     * 参数 `$read_write` :
     *   可选值：STREAM_FILTER_READ、STREAM_FILTER_WRITE和/或STREAM_FILTER_ALL
     * @param string $filtername 过滤器
     * @param int    $read_write 读写模式
     * @param mixed  $params     相关参数
     * @return resource
     */
    public function append($filtername, $read_write = null, $params = null)
    {
        return stream_filter_append($this->context, $filtername, $read_write, $params);
    }

    /**
     * 将前置过滤器附加到流
     *
     * 参数 `$read_write` :
     *   可选值：STREAM_FILTER_READ、STREAM_FILTER_WRITE和/或STREAM_FILTER_ALL
     * @param string $filtername 过滤器
     * @param int    $read_write 读写模式
     * @param mixed  $params     相关参数
     * @return resource
     */
    public function prepend($filtername, $read_write = null, $params = null)
    {
        return stream_filter_prepend($this->context, $filtername, $read_write, $params);
    }

    /**
     * 注册用户定义的流过滤器
     * @param string $filtername 过滤器名称
     * @param string $classname  类全限定名
     * @return bool
     */
    public static function register($filtername, $classname)
    {
        return stream_filter_register($filtername, $classname);
    }

    /**
     * 从资源流里移除某个过滤器
     * @param resource $stream_filter 需要被移除的资源流过滤器
     * @return bool
     */
    public static function remove($stream_filter)
    {
        return stream_filter_remove($stream_filter);
    }

}