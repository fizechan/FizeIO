<?php

namespace fize\io;

/**
 * 文件
 */
abstract class FileAbstract
{
    /**
     * @var resource 文件流
     */
    protected $stream;

    /**
     * 构造
     * @param resource $stream 流
     */
    public function __construct($stream = null)
    {
        $this->stream = $stream;
    }

    /**
     * 测试文件指针是否到了文件结束的位置
     * @return bool
     */
    public function eof(): bool
    {
        return feof($this->stream);
    }

    /**
     * 将缓冲内容输出到文件
     * @return bool
     */
    public function flush(): bool
    {
        return fflush($this->stream);
    }

    /**
     * 从文件指针中读取一个字符。 碰到 EOF 则返回 FALSE 。
     * @return string 如果碰到 EOF 则返回 FALSE。
     */
    public function getc(): string
    {
        return fgetc($this->stream);
    }

    /**
     * 从文件指针中读取一行
     *
     * 参数 `$length` :
     *   默认是 1024 字节。
     *   实际返回的字节是 $length - 1
     * @param int|null $length 规定要读取的字节数
     * @return string 若失败，则返回 false。
     */
    public function gets(int $length = null): string
    {
        if (is_null($length)) {
            $rst = fgets($this->stream);
        } else {
            $rst = fgets($this->stream, $length);
        }
        return $rst;
    }

    /**
     * 从文件指针中读取一行并过滤掉HTML和PHP标记。
     *
     * 参数 `$length` :
     *   默认是 1024 字节
     * 参数 `$allowable_tags` :
     *   形如“<p>,<b>”
     * @param int|null    $length         规定要读取的字节数
     * @param string|null $allowable_tags 规定不会被删除的标签
     * @return string
     * @deprecated PHP7.3不建议使用该方法
     */
    public function getss(int $length = null, string $allowable_tags = null): string
    {
        if (is_null($length)) {
            $rst = fgetss($this->stream);
        } else {
            $rst = fgetss($this->stream, $length, $allowable_tags);
        }
        return $rst;
    }

    /**
     * 轻便的咨询文件锁定
     *
     * 参数 `$operation` :
     *   可选值：[LOCK_SH|LOCK_EX|LOCK_UN]
     * @param int      $operation  操作
     * @param int|null $wouldblock 如果锁定会堵塞的话返回1
     * @return bool
     */
    public function lock(int $operation, int &$wouldblock = null): bool
    {
        return flock($this->stream, $operation, $wouldblock);
    }

    /**
     * 输出文件指针处的所有剩余数据
     * @return int 返回剩余数据字节数
     */
    public function passthru(): int
    {
        return fpassthru($this->stream);
    }

    /**
     * 写入文件（可安全用于二进制文件）
     * @param string   $string 要写入的字符串
     * @param int|null $length 指定写入长度
     * @return int 如果失败返回false
     */
    public function puts(string $string, int $length = null): int
    {
        if (is_null($length)) {
            $rst = fputs($this->stream, $string);
        } else {
            $rst = fputs($this->stream, $string, $length);
        }
        return $rst;
    }

    /**
     * 读取文件（可安全用于二进制文件）
     * @param int $length
     * @return string
     */
    public function read(int $length): string
    {
        return fread($this->stream, $length);
    }

    /**
     * 从文件中格式化输入
     * @param string $format
     * @return array|int|false|null
     */
    public function scanf(string $format)
    {
        return fscanf($this->stream, $format);
    }

    /**
     * 在文件指针中定位
     *
     * 参数 `$offset` :
     *   要移动到文件尾之前的位置，需要给 offset 传递一个负值，并设置 whence 为 SEEK_END。
     * 参数 `$whence` :
     *   SEEK_SET - 设定位置等于 offset 字节。
     *   SEEK_CUR - 设定位置为当前位置加上 offset。
     *   SEEK_END - 设定位置为文件尾加上 offset。
     * @param int $offset 偏移量
     * @param int $whence 设置方式
     * @return int
     */
    public function seek(int $offset, int $whence = 0): int
    {
        return fseek($this->stream, $offset, $whence);
    }

    /**
     * 返回文件指针读/写的位置
     * @return int
     */
    public function tell(): int
    {
        return ftell($this->stream);
    }

    /**
     * 将文件截断到给定的长度
     * @param int $size 指定长度
     * @return bool
     */
    public function truncate(int $size): bool
    {
        return ftruncate($this->stream, $size);
    }

    /**
     * 写入文件（可安全用于二进制文件）
     * @param string   $string 要写入的字符串
     * @param int|null $length 指定写入长度
     * @return int 失败时返回false
     */
    public function write(string $string, int $length = null): int
    {
        if (is_null($length)) {
            $rst = fwrite($this->stream, $string);
        } else {
            $rst = fwrite($this->stream, $string, $length);
        }
        return $rst;
    }

    /**
     * 倒回文件指针的位置
     * @return bool
     */
    public function rewind(): bool
    {
        return rewind($this->stream);
    }

    /**
     * 设置当前打开文件的缓冲大小。
     * @param int $buffer 规定缓冲大小，以字节计。
     * @return int 未启动句柄时返回false；否则如果成功，该函数返回 0，否则返回 EOF。
     */
    public function setBuffer(int $buffer): int
    {
        return set_file_buffer($this->stream, $buffer);
    }
}