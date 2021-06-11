<?php

namespace fize\io;

/**
 * 进程文件
 */
class PFile
{

    /**
     * @var resource 文件句柄
     */
    private $handle;

    public function __construct(string $command, string $mode)
    {
        $this->handle = popen($command, $mode);
    }

    /**
     * 析构
     */
    public function __destruct()
    {
        if ($this->handle) {
            $this->close();
        }
    }

    /**
     * 关闭
     */
    public function close()
    {
        pclose($this->handle);
    }

    /**
     * @return bool 指针是否到了文件结束的位置
     */
    public function eof(): bool
    {
        return feof($this->handle);
    }

    /**
     * 读取一行
     * @return false|string EOF时返回false
     */
    public function gets()
    {
        return fgets($this->handle);
    }

    /**
     * 从文件指针中读取一行并过滤掉HTML和PHP标记。
     *
     * 参数 `$length` :
     *   默认是 1024 字节
     * 参数 `$allowable_tags` :
     *   形如“<p>,<b>”
     * @param int         $length         规定要读取的字节数
     * @param string|null $allowable_tags 规定不会被删除的标签
     * @return string
     * @deprecated PHP7.3不建议使用该方法
     */
    public function getss(int $length = null, string $allowable_tags = null): string
    {
        return fgetss($this->handle, $length, $allowable_tags);
    }

    /**
     * 读取
     *
     * 可安全用于二进制文件
     * @param int $length 最多读取length个字节
     * @return false|string
     */
    public function read(int $length)
    {
        return fread($this->handle, $length);
    }

    /**
     * 写入文件
     *
     * 可安全用于二进制文件
     * @param string   $string 要写入字符串
     * @param int|null $length 最多写入length个字节
     * @return false|int 返回写入的字符数，出现错误时则返回 false 。
     */
    public function write(string $string, int $length = null)
    {
        return fwrite($this->handle, $string, $length);
    }

}