<?php

namespace fize\io;

class StreamWrapper
{
    /**
     * 注册一个用 PHP 类实现的 URL 封装协议
     *
     * 参数 `$flags` :
     *   如果协议是URL协议，则应设置为STREAM_IS_URL。默认值是0,即本地流。
     * @param string $protocol  待注册的封装的名字
     * @param string $classname 实现了protocol的类名
     * @param int    $flags     标识
     * @return bool
     */
    public static function register($protocol, $classname, $flags = 0)
    {
        return stream_wrapper_register($protocol, $classname, $flags);
    }

    /**
     * 恢复以前未注册的内置包装器
     * @param string $protocol 待恢复的封装的名字
     * @return bool
     */
    public static function restore($protocol)
    {
        return stream_wrapper_restore($protocol);
    }

    /**
     * 卸载封装协议
     * @param string $protocol 待卸载的封装的名字
     * @return bool
     */
    public static function unregister($protocol)
    {
        return stream_wrapper_unregister($protocol);
    }
}