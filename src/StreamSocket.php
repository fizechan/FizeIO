<?php

namespace fize\io;

/**
 * 流套接字
 */
class StreamSocket extends Stream
{

    /**
     * 在已连接的套接字上打开/关闭加密
     * @param bool     $enable         是否开启加密
     * @param int|null $crypto_type    可选的加密类型
     * @param resource $session_stream 用来自session_stream的设置为流。
     * @return bool|int 成功true，失败false。没有足够数据时返回0
     */
    public function enableCrypto(bool $enable, int $crypto_type = null, $session_stream = null)
    {
        if (is_null($session_stream)) {
            return stream_socket_enable_crypto($this->stream, $enable, $crypto_type);
        }
        return stream_socket_enable_crypto($this->stream, $enable, $crypto_type, $session_stream);
    }

    /**
     * 获取本地或者远程的套接字名称
     *
     * 参数 `$want_peer` :
     *   如果设置为 TRUE ，那么将返回 remote 套接字连接名称；如果设置为 FALSE 则返回 local 套接字连接名称。
     * @param int $want_peer 是否远程套接字
     * @return string
     */
    public function getName(int $want_peer): string
    {
        return stream_socket_get_name($this->stream, $want_peer);
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
     * @param int $domain   使用的协议族
     * @param int $type     通信类型
     * @param int $protocol 使用的传输协议
     * @return array 数组包括了两个socket资源，失败返回false
     */
    public static function pair(int $domain, int $type, int $protocol): array
    {
        return stream_socket_pair($domain, $type, $protocol);
    }

    /**
     * 从套接字接收数据，无论是否连接
     * @param int         $length  长度
     * @param int         $flags   标识
     * @param string|null $address 将使用远程套接字的地址填充。
     * @return string 以字符串的形式返回读取的数据
     */
    public function recvfrom(int $length, int $flags = 0, string &$address = null): string
    {
        return stream_socket_recvfrom($this->stream, $length, $flags, $address);
    }

    /**
     * 向套接字发送消息，不管它是否连接
     * @param string      $data    消息
     * @param int         $flags   标识
     * @param string|null $address 将使用远程套接字的地址填充。
     * @return int 以整数形式返回结果代码。
     */
    public function sendto(string $data, int $flags = 0, string $address = null): int
    {
        return stream_socket_sendto($this->stream, $data, $flags, $address);
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
    public function shutdown(int $how): bool
    {
        return stream_socket_shutdown($this->stream, $how);
    }

    /**
     * 接受由 Stream::socketServer() 创建的套接字连接
     *
     * 参数 `$timeout` :
     *   输入的时间需以秒为单位。
     * 参数 `$peername` :
     *   如果包含该参数并且是可以从选中的传输数据中获取到，则将被设置给连接中的客户端主机的名称（地址）
     * @param float|null  $timeout  覆盖默认的套接字接受的超时时限
     * @param string|null $peername 设置给连接中的客户端主机的名称（地址）
     * @return StreamSocket|false 失败时返回false
     */
    public function accept(float $timeout = null, string &$peername = null)
    {
        if (is_null($timeout)) {
            $stream = stream_socket_accept($this->stream);
        } else {
            $stream = stream_socket_accept($this->stream, $timeout, $peername);
        }
        if ($stream === false) {
            return false;
        }
        return new StreamSocket($stream);
    }

    /**
     * 打开Internet或Unix域套接字连接
     *
     * 参数 `$flags` :
     *   选择仅限于STREAM_CLIENT_CONNECT(默认)、STREAM_CLIENT_ASYNC_CONNECT和STREAM_CLIENT_PERSISTENT。
     * @param string      $remote_socket 要连接到的套接字的地址。
     * @param int|null    $errno         错误码
     * @param string|null $errstr        错误信息
     * @param float|null  $timeout       超时时限。输入的时间需以秒为单位。
     * @param int         $flags         标识
     * @param resource    $context       使用stream_context_create()创建的有效上下文资源。
     * @return StreamSocket|false 失败时返回false
     */
    public static function client(string $remote_socket, int &$errno = null, string &$errstr = null, float $timeout = null, int $flags = 4, $context = null)
    {
        if (is_null($context)) {
            $stream = stream_socket_client($remote_socket, $errno, $errstr, $timeout, $flags);
        } else {
            $stream = stream_socket_client($remote_socket, $errno, $errstr, $timeout, $flags, $context);
        }
        if ($stream === false) {
            return false;
        }
        return new StreamSocket($stream);
    }

    /**
     * 创建Internet或Unix域服务器套接字
     *
     * 参数 `$local_socket` :
     *   创建的套接字类型由使用标准URL格式transport: transport://target指定的传输类型决定。
     * @param string      $local_socket 套接字字符串
     * @param int|null    $errno        错误码
     * @param string|null $errstr       错误描述
     * @param int         $flags        标识
     * @param resource    $context      有效上下文资源。
     * @return StreamSocket|false 失败时返回false
     */
    public static function server(string $local_socket, int &$errno = null, string &$errstr = null, int $flags = 12, $context = null)
    {
        if (is_null($context)) {
            $stream = stream_socket_server($local_socket, $errno, $errstr, $flags);
        } else {
            $stream = stream_socket_server($local_socket, $errno, $errstr, $flags, $context);
        }
        if ($stream === false) {
            return false;
        }
        return new StreamSocket($stream);
    }
}