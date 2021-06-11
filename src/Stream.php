<?php


namespace fize\io;

/**
 * 流
 */
class Stream
{

    /**
     * @var resource 资源流上下文
     */
    protected $context;

    /**
     * 初始化
     * @param resource|string $resource 资源流/数据包/上下文/文件路径
     * @param string          $mode     打开模式
     */
    public function __construct($resource, $mode = null)
    {
        if (is_resource($resource)) {
            $this->context = $resource;
        } else {
            $this->context = fopen($resource, $mode);
        }
    }

    /**
     * 析构
     *
     * 清理资源对象，防止内存泄漏
     */
    public function __destruct()
    {
        if ($this->context && is_resource($this->context) && get_resource_type($this->context) == 'stream') {
            fclose($this->context);
        }
    }

    /**
     * 返回当前上下文
     * @return resource
     */
    public function get()
    {
        return $this->context;
    }

    /**
     * 将数据复制到另一个流
     * @param resource $dest      目标流
     * @param int      $maxlength 最大长度
     * @param int      $offset    偏移量
     * @return int 失败时返回false
     */
    public function copyToStream($dest, $maxlength = -1, $offset = 0)
    {
        return stream_copy_to_stream($this->context, $dest, $maxlength, $offset);
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
    public function getContents($maxlength = -1, $offset = -1)
    {
        return stream_get_contents($this->context, $maxlength, $offset);
    }

    /**
     * 获取已注册的数据流过滤器列表
     * @return array
     */
    public static function getFilters()
    {
        return stream_get_filters();
    }

    /**
     * 从资源流里读取一行直到给定的定界符
     * @param int    $length 需要从句柄里读取的字节数。
     * @param string $ending 可选参数，字符串定界符。
     * @return string 如果发生错误，则返回 FALSE.
     */
    public function getLine($length, $ending = null)
    {
        return stream_get_line($this->context, $length, $ending);
    }

    /**
     *  从封装协议文件指针中取得报头／元数据
     * @return array
     */
    public function getMetaData()
    {
        return stream_get_meta_data($this->context);
    }

    /**
     * 获取已注册的套接字传输协议列表
     * @return array
     */
    public static function getTransports()
    {
        return stream_get_transports();
    }

    /**
     * 获取已注册的流类型
     * @return array
     */
    public static function getWrappers()
    {
        return stream_get_wrappers();
    }

    /**
     * 检查流是否是本地流
     * @param mixed $stream_or_url 可以指定流或者URL
     * @return bool
     */
    public function isLocal($stream_or_url = null)
    {
        if (is_null($stream_or_url)) {
            $stream_or_url = $this->context;
        }
        return stream_is_local($stream_or_url);
    }

    /**
     * 确定流是否引用有效的终端类型设备
     * @return bool
     * @since        PHP7.2
     */
    public function isatty()
    {
        return stream_isatty($this->context);
    }

    /**
     * 根据包含路径解析文件名
     * @param string $filename 包含路径
     * @return string 失败时返回false
     */
    public static function resolveIncludePath($filename)
    {
        return stream_resolve_include_path($filename);
    }

    /**
     * 对流的给定数组运行select()系统调用，超时由tv_sec和tv_usec指定
     * @param array $read    流组成的数组，以查看是否有字符可读
     * @param array $write   流组成的数组，以查看是否有字符可写
     * @param array $except  流组成的数组，以查看是否可导出
     * @param int   $tv_sec  指定秒数
     * @param int   $tv_usec 指定微秒数
     * @return int
     */
    public static function select(array &$read, array &$write, array &$except, $tv_sec, $tv_usec = null)
    {
        return stream_select($read, $write, $except, $tv_sec, $tv_usec);
    }

    /**
     * 为资源流设置阻塞或者非阻塞模式
     * @param int $mode 模式
     * @return bool
     */
    public function setBlocking($mode)
    {
        return stream_set_blocking($this->context, $mode);
    }

    /**
     * 设置资源流区块大小
     * @param int $chunk_size 想设置的新的区块大小。
     * @return int 失败时返回false
     */
    public function setChunkSize($chunk_size)
    {
        return stream_set_chunk_size($this->context, $chunk_size);
    }

    /**
     * 设置流上的读取文件缓冲
     * @param int $buffer 缓冲大小
     * @return int
     */
    public function setReadBuffer($buffer)
    {
        return stream_set_read_buffer($this->context, $buffer);
    }

    /**
     * 设置流超时时间
     * @param int $seconds      指定秒
     * @param int $microseconds 指定毫秒
     * @return bool
     */
    public function setTimeout($seconds, $microseconds = null)
    {
        return stream_set_timeout($this->context, $seconds, $microseconds);
    }

    /**
     * 设置流上的写文件缓冲
     * @param int $buffer 缓冲大小
     * @return int
     */
    public function setWriteBuffer($buffer)
    {
        return stream_set_write_buffer($this->context, $buffer);
    }



    /**
     * 表示流是否支持锁定
     * @return bool
     */
    public function supportsLock()
    {
        return stream_supports_lock($this->context);
    }
}
