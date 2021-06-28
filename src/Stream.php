<?php


namespace fize\io;

/**
 * 流处理
 */
class Stream
{

    /**
     * @var resource 资源流上下文
     */
    protected $stream;

    /**
     * 初始化
     * @param resource $resource 资源流/数据包/上下文
     */
    public function __construct($resource)
    {
        $this->stream = $resource;
    }

    /**
     * 析构
     *
     * 清理资源对象，防止内存泄漏
     */
    public function __destruct()
    {
        if ($this->stream && is_resource($this->stream) && get_resource_type($this->stream) == 'stream') {
            fclose($this->stream);
        }
    }

    /**
     * 返回当前上下文
     * @notice 请谨慎使用该方法
     * @return resource
     */
    public function get()
    {
        return $this->stream;
    }

    /**
     * 将数据复制到另一个流
     * @param resource $dest      目标流
     * @param int      $maxlength 最大长度
     * @param int      $offset    偏移量
     * @return int 失败时返回false
     */
    public function copyToStream($dest, int $maxlength = -1, int $offset = 0): int
    {
        return stream_copy_to_stream($this->stream, $dest, $maxlength, $offset);
    }

    /**
     * 读取资源流到一个字符串
     *
     * 参数 `$maxlength` :
     *   默认是-1（读取全部的缓冲数据）。
     * 参数 `$offset` :
     *   如果这个数字是负数，就不进行查找，直接从当前位置开始读取。
     * @param int $maxlength 需要读取的最大的字节数
     * @param int $offset    在读取数据之前先查找指定的偏移量
     * @return string 失败时返回false
     */
    public function getContents(int $maxlength = -1, int $offset = -1): string
    {
        return stream_get_contents($this->stream, $maxlength, $offset);
    }

    /**
     * 获取已注册的数据流过滤器列表
     * @return array
     */
    public static function getFilters(): array
    {
        return stream_get_filters();
    }

    /**
     * 从资源流里读取一行直到给定的定界符
     * @param int         $length 需要从句柄里读取的字节数。
     * @param string|null $ending 可选参数，字符串定界符。
     * @return string 如果发生错误，则返回 FALSE.
     */
    public function getLine(int $length, string $ending = null): string
    {
        return stream_get_line($this->stream, $length, $ending);
    }

    /**
     *  从封装协议文件指针中取得报头／元数据
     * @return array
     */
    public function getMetaData(): array
    {
        return stream_get_meta_data($this->stream);
    }

    /**
     * 获取已注册的套接字传输协议列表
     * @return array
     */
    public static function getTransports(): array
    {
        return stream_get_transports();
    }

    /**
     * 获取已注册的流类型
     * @return array
     */
    public static function getWrappers(): array
    {
        return stream_get_wrappers();
    }

    /**
     * 检查流是否是本地流
     * @param mixed $stream_or_url 可以指定流或者URL
     * @return bool
     */
    public function isLocal($stream_or_url = null): bool
    {
        if (is_null($stream_or_url)) {
            $stream_or_url = $this->stream;
        }
        return stream_is_local($stream_or_url);
    }

    /**
     * 确定流是否引用有效的终端类型设备
     * @return bool
     * @since        PHP7.2
     */
    public function isatty(): bool
    {
        return stream_isatty($this->stream);
    }

    /**
     * 根据包含路径解析文件名
     * @param string $filename 包含路径
     * @return string 失败时返回false
     */
    public static function resolveIncludePath(string $filename): string
    {
        return stream_resolve_include_path($filename);
    }

    /**
     * 对流的给定数组运行select()系统调用，超时由tv_sec和tv_usec指定
     * @param array    $read    流组成的数组，以查看是否有字符可读
     * @param array    $write   流组成的数组，以查看是否有字符可写
     * @param array    $except  流组成的数组，以查看是否可导出
     * @param int      $tv_sec  指定秒数
     * @param int|null $tv_usec 指定微秒数
     * @return int
     */
    public static function select(array &$read, array &$write, array &$except, int $tv_sec, int $tv_usec = null): int
    {
        return stream_select($read, $write, $except, $tv_sec, $tv_usec);
    }

    /**
     * 为资源流设置阻塞或者非阻塞模式
     * @param int $mode 模式
     * @return bool
     */
    public function setBlocking(int $mode): bool
    {
        return stream_set_blocking($this->stream, $mode);
    }

    /**
     * 设置资源流区块大小
     * @param int $chunk_size 想设置的新的区块大小。
     * @return int 失败时返回false
     */
    public function setChunkSize(int $chunk_size): int
    {
        return stream_set_chunk_size($this->stream, $chunk_size);
    }

    /**
     * 设置流上的读取文件缓冲
     * @param int $buffer 缓冲大小
     * @return int
     */
    public function setReadBuffer(int $buffer): int
    {
        return stream_set_read_buffer($this->stream, $buffer);
    }

    /**
     * 设置流超时时间
     * @param int      $seconds      指定秒
     * @param int|null $microseconds 指定毫秒
     * @return bool
     */
    public function setTimeout(int $seconds, int $microseconds = null): bool
    {
        return stream_set_timeout($this->stream, $seconds, $microseconds);
    }

    /**
     * 设置流上的写文件缓冲
     * @param int $buffer 缓冲大小
     * @return int
     */
    public function setWriteBuffer(int $buffer): int
    {
        return stream_set_write_buffer($this->stream, $buffer);
    }


    /**
     * 表示流是否支持锁定
     * @return bool
     */
    public function supportsLock(): bool
    {
        return stream_supports_lock($this->stream);
    }
}
