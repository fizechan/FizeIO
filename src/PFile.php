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

    /**
     * 构造
     * @param string $command 命令
     * @param string $mode    模式
     */
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
            pclose($this->handle);
            $this->handle = null;
        }
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
     * 输出文件指针处的所有剩余数据
     * @return int 返回剩余数据字节数
     */
    public function passthru(): int
    {
        return fpassthru($this->handle);
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