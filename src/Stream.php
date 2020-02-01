<?php


namespace fize\io;

/**
 * Stream 流处理
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
     * @param string $mode 打开模式
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
        if($this->context && is_resource($this->context) && get_resource_type($this->context) == 'stream') {
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
     * 将桶附加到队列
     * @todo 本函数还未编写文档，仅有参数列表。
     * @param resource $brigade 队列
     * @param object $bucket 桶
     */
    public static function bucketAppend($brigade, $bucket)
    {
        stream_bucket_append($brigade, $bucket);
    }

    /**
     * 从队列中返回一个bucket对象，用于操作
     * @todo 本函数还未编写文档，仅有参数列表。
     * @param resource $brigade 队列
     * @return object
     */
    public static function bucketMakeWriteable($brigade)
    {
        return stream_bucket_make_writeable($brigade);
    }

    /**
     * 创建一个用于当前流的新桶
     * @todo 本函数还未编写文档，仅有参数列表。
     * @param resource $stream 流
     * @param string $buffer 缓存区
     * @return object
     */
    public static function bucketNew($stream, $buffer)
    {
        return stream_bucket_new($stream, $buffer);
    }

    /**
     * 为队列准备桶
     * @todo 官方文档过于复杂，暂时不测试
     * @param resource $brigade 队列
     * @param resource $bucket 桶
     */
    public static function bucketPrepend($brigade, $bucket)
    {
        stream_bucket_prepend($brigade, $bucket);
    }

    /**
     * 创建资源流上下文
     *
     * 参数 `$options` :
     *   格式如下：$arr['wrapper']['option'] = $value 。
     * 参数 `$params` :
     *   必须是 $arr['parameter'] = $value 格式的关联数组。
     *   请参考 context parameters 里的标准资源流参数列表。
     * @param array $options 选项，必须是一个二维关联数组。
     * @param array $params 参数
     * @return resource
     */
    public static function contextCreate(array $options = null, array $params = null)
    {
        return stream_context_create($options, $params);
    }

    /**
     * 检索默认流上下文
     * @param array $options 选项
     * @return resource
     */
    public static function contextGetDefault(array $options = [])
    {
        return stream_context_get_default($options);
    }

    /**
     * 获取资源流/数据包/上下文的参数
     * @return array 返回一个包含有原参数的关联数组。
     */
    public function contextGetOptions()
    {
        return stream_context_get_options($this->context);
    }

    /**
     * 从上下文检索参数
     * @return array 参数
     */
    public function contextGetParams()
    {
        return stream_context_get_params($this->context);
    }

    /**
     * 设置默认流上下文
     * @param array $options 选项
     * @return resource 返回默认流
     */
    public static function contextSetDefault(array $options)
    {
        return stream_context_set_default($options);
    }

    /**
     * 对资源流、数据包或者上下文设置参数
     * @param array $options 选项
     * @return bool
     */
    public function contextSetOption(array $options)
    {
        return stream_context_set_option($this->context, $options);
    }

    /**
     * 设置流/包装器/上下文的参数
     * @param array $params 参数
     * @return bool
     */
    public function contextSetParams(array $params)
    {
        return stream_context_set_params($this->context, $params);
    }

    /**
     * 将数据复制到另一个流
     * @param resource $dest 目标流
     * @param int $maxlength 最大长度
     * @param int $offset 偏移量
     * @return int 失败时返回false
     */
    public function copyToStream($dest, $maxlength = -1, $offset = 0)
    {
        return stream_copy_to_stream($this->context, $dest, $maxlength, $offset);
    }

    /**
     * 将后置过滤器附加到流
     *
     * 参数 `$read_write` :
     *   可选值：STREAM_FILTER_READ、STREAM_FILTER_WRITE和/或STREAM_FILTER_ALL
     * @param string $filtername 过滤器
     * @param int $read_write 读写模式
     * @param mixed $params 相关参数
     * @return resource
     */
    public function filterAppend($filtername, $read_write = null, $params = null)
    {
        return stream_filter_append($this->context, $filtername, $read_write, $params);
    }

    /**
     * 将前置过滤器附加到流
     *
     * 参数 `$read_write` :
     *   可选值：STREAM_FILTER_READ、STREAM_FILTER_WRITE和/或STREAM_FILTER_ALL
     * @param string $filtername 过滤器
     * @param int $read_write 读写模式
     * @param mixed $params 相关参数
     * @return resource
     */
    public function filterPrepend($filtername, $read_write = null, $params = null)
    {
        return stream_filter_prepend($this->context, $filtername, $read_write, $params);
    }

    /**
     * 注册用户定义的流过滤器
     * @param string $filtername 过滤器名称
     * @param string $classname 类全限定名
     * @return bool
     */
    public static function filterRegister($filtername, $classname)
    {
        return stream_filter_register($filtername, $classname);
    }

    /**
     * 从资源流里移除某个过滤器
     * @param resource $stream_filter 需要被移除的资源流过滤器
     * @return bool
     */
    public static function filterRemove($stream_filter)
    {
        return stream_filter_remove($stream_filter);
    }

    /**
     * 读取资源流到一个字符串
     *
     * 参数 `$maxlength` :
     *   默认是-1（读取全部的缓冲数据）。
     * 参数 `$offset` :
     *   如果这个数字是负数，就不进行查找，直接从当前位置开始读取。
     * @param int $maxlength 需要读取的最大的字节数
     * @param int $offset 在读取数据之前先查找指定的偏移量
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
     * @param int $length 需要从句柄里读取的字节数。
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
        if(is_null($stream_or_url)) {
            $stream_or_url = $this->context;
        }
        return stream_is_local($stream_or_url);
    }

    /**
     * 确定流是否引用有效的终端类型设备
     * @return bool
     * @since PHP7.2
     * @noinspection PhpElementIsNotAvailableInCurrentPhpVersionInspection
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
     * @param array $read 流组成的数组，以查看是否有字符可读
     * @param array $write 流组成的数组，以查看是否有字符可写
     * @param array $except 流组成的数组，以查看是否可导出
     * @param int $tv_sec 指定秒数
     * @param int $tv_usec 指定微秒数
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
        return stream_set_chunk_size($this->context , $chunk_size);
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
     * @param int $seconds 指定秒
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
     * 接受由 Stream::socketServer() 创建的套接字连接
     *
     * 参数 `$timeout` :
     *   输入的时间需以秒为单位。
     * 参数 `$peername` :
     *   如果包含该参数并且是可以从选中的传输数据中获取到，则将被设置给连接中的客户端主机的名称（地址）
     * @param float $timeout 覆盖默认的套接字接受的超时时限
     * @param string $peername 设置给连接中的客户端主机的名称（地址）
     * @return resource 失败时返回false
     */
    public function socketAccept($timeout = null, &$peername = null)
    {
        if(is_null($timeout)) {
            return stream_socket_accept($this->context);
        }
        return stream_socket_accept($this->context, $timeout, $peername);
    }

    /**
     * 打开Internet或Unix域套接字连接
     *
     * 参数 `$flags` :
     *   选择仅限于STREAM_CLIENT_CONNECT(默认)、STREAM_CLIENT_ASYNC_CONNECT和STREAM_CLIENT_PERSISTENT。
     * @param string $remote_socket 要连接到的套接字的地址。
     * @param int $errno 错误码
     * @param string $errstr 错误信息
     * @param float $timeout 超时时限。输入的时间需以秒为单位。
     * @param int $flags 标识
     * @param resource $context 使用stream_context_create()创建的有效上下文资源。
     * @return resource 失败时返回false
     */
    public static function socketClient($remote_socket, &$errno = null, &$errstr = null, $timeout = null, $flags = 4, $context = null)
    {
        if (is_null($context)) {
            return stream_socket_client($remote_socket, $errno, $errstr, $timeout, $flags);
        }
        return stream_socket_client($remote_socket, $errno, $errstr, $timeout, $flags, $context);
    }

    /**
     * 在已连接的套接字上打开/关闭加密
     * @param bool $enable 是否开启加密
     * @param int $crypto_type 可选的加密类型
     * @param resource $session_stream 用来自session_stream的设置为流。
     * @return mixed 成功true，失败false。没有足够数据时返回0
     */
    public function socketEnableCrypto($enable, $crypto_type = null, $session_stream = null)
    {
        if (is_null($session_stream)) {
            return stream_socket_enable_crypto($this->context, $enable, $crypto_type);
        }
        return stream_socket_enable_crypto($this->context, $enable, $crypto_type, $session_stream);
    }

    /**
     * 获取本地或者远程的套接字名称
     *
     * 参数 `$want_peer` :
     *   如果设置为 TRUE ，那么将返回 remote 套接字连接名称；如果设置为 FALSE 则返回 local 套接字连接名称。
     * @param int $want_peer 是否远程套接字
     * @return string
     */
    public function socketGetName($want_peer)
    {
        return stream_socket_get_name($this->context, $want_peer);
    }

    /**
     * 创建一对完全一样的网络套接字连接流
     *
     * 参数 `$domain` :
     *   可选值：STREAM_PF_INET, STREAM_PF_INET6 or STREAM_PF_UNIX
     * 参数 `$type` :
     *   可选值：STREAM_SOCK_DGRAM, STREAM_SOCK_RAW, STREAM_SOCK_RDM, STREAM_SOCK_SEQPACKET or STREAM_SOCK_STREAM
     * 参数 `$protocol` :
     *   可选值：STREAM_IPPROTO_ICMP, STREAM_IPPROTO_IP, STREAM_IPPROTO_RAW, STREAM_IPPROTO_TCP or STREAM_IPPROTO_UDP
     * @param int $domain 使用的协议族
     * @param int $type 通信类型
     * @param int $protocol 使用的传输协议
     * @return array 数组包括了两个socket资源，失败返回false
     */
    public static function socketPair($domain, $type, $protocol)
    {
        return stream_socket_pair($domain, $type, $protocol);
    }

    /**
     * 从套接字接收数据，无论是否连接
     * @param int $length 长度
     * @param int $flags 标识
     * @param string $address 将使用远程套接字的地址填充。
     * @return string 以字符串的形式返回读取的数据
     */
    public function socketRecvfrom($length, $flags = 0, &$address = null)
    {
        return stream_socket_recvfrom($this->context, $length, $flags, $address);
    }

    /**
     * 向套接字发送消息，不管它是否连接
     * @param string $data 消息
     * @param int $flags 标识
     * @param string $address 将使用远程套接字的地址填充。
     * @return int 以整数形式返回结果代码。
     */
    public function socketSendto($data, $flags = 0, $address = null)
    {
        return stream_socket_sendto($this->context, $data, $flags, $address);
    }

    /**
     * 创建Internet或Unix域服务器套接字
     *
     * 参数 `$local_socket` :
     *   创建的套接字类型由使用标准URL格式transport: transport://target指定的传输类型决定。
     * @param string $local_socket 套接字字符串
     * @param int $errno 错误码
     * @param string $errstr 错误描述
     * @param int $flags 标识
     * @param resource $context 有效上下文资源。
     * @return resource 失败时返回false
     */
    public static function socketServer($local_socket, &$errno = null, &$errstr = null, $flags = 12, $context = null)
    {
        if(is_null($context)) {
            return stream_socket_server($local_socket, $errno, $errstr, $flags);
        }
        return stream_socket_server($local_socket, $errno, $errstr, $flags, $context);
    }

    /**
     * 关闭全双工连接
     *
     * 参数 `$stream` :
     *   (例如，用stream_socket_client()打开)
     * 参数 `$how` :
     *   以下常量之一:STREAM_SHUT_RD(禁用进一步的接收)、STREAM_SHUT_WR(禁用进一步的传输)或STREAM_SHUT_RDWR(禁用进一步的接收和传输)。
     * @param int $how 定义如何处理
     * @return bool
     */
    public function socketShutdown($how)
    {
        return stream_socket_shutdown($this->context, $how);
    }

    /**
     * 表示流是否支持锁定
     * @return bool
     */
    public function supportsLock()
    {
        return stream_supports_lock($this->context);
    }

    /**
     * 注册一个用 PHP 类实现的 URL 封装协议
     *
     * 参数 `$flags` :
     *   如果协议是URL协议，则应设置为STREAM_IS_URL。默认值是0,即本地流。
     * @param string $protocol 待注册的封装的名字
     * @param string $classname 实现了protocol的类名
     * @param int $flags 标识
     * @return bool
     */
    public static function wrapperRegister($protocol, $classname, $flags = 0)
    {
        return stream_wrapper_register($protocol, $classname, $flags);
    }

    /**
     * 恢复以前未注册的内置包装器
     * @param string $protocol 待恢复的封装的名字
     * @return bool
     */
    public static function wrapperRestore($protocol)
    {
        return stream_wrapper_restore($protocol);
    }

    /**
     * 卸载封装协议
     * @param string $protocol 待卸载的封装的名字
     * @return bool
     */
    public static function wrapperUnregister($protocol)
    {
        return stream_wrapper_unregister($protocol);
    }
}
