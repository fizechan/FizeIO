<?php

use fize\io\Stream;
use fize\io\File;
use PHPUnit\Framework\TestCase;

class TestStream extends TestCase
{

    public function testCopyToStream()
    {
        $stream = new Stream('https://www.baidu.com', 'r');
        $dest = fopen('../temp/baidu.txt', 'w');
        $rst = $stream->copyToStream($dest);
        var_dump($rst);
        self::assertIsInt($rst);
    }

    public function testGetContents()
    {
        $stream = new Stream('https://www.baidu.com', 'r');
        $content = $stream->getContents();
        var_dump($content);
        self::assertIsString($content);
    }

    public function testGetFilters()
    {
        $filters = Stream::getFilters();
        var_dump($filters);
        self::assertIsArray($filters);
    }

    public function testGetLine()
    {
        $stream = new Stream('https://www.baidu.com', 'r');
        $line1 = $stream->getLine(100);
        var_dump($line1);
        self::assertIsString($line1);
        $line2 = $stream->getLine(100, ">");
        var_dump($line2);
        self::assertIsString($line2);
    }

    public function testGetMetaData()
    {
        $stream = new Stream('https://www.baidu.com', 'r');
        $meta = $stream->getMetaData();
        var_dump($meta);
        self::assertIsArray($meta);
    }

    public function testGetTransports()
    {
        $transports = Stream::getTransports();
        var_dump($transports);
        self::assertIsArray($transports);
    }

    public function testGetWrappers()
    {
        $wrappers = Stream::getWrappers();
        var_dump($wrappers);
        self::assertIsArray($wrappers);
    }

    public function testIsLocal()
    {
        $stream = new Stream('../temp/testStreamFilterRemove.txt', 'w+');
        $rst1 = $stream->isLocal();
        var_dump($rst1);
        self::assertTrue($rst1);
        $rst2 = $stream->isLocal('https://www.baidu.com');
        var_dump($rst2);
        self::assertFalse($rst2);
    }

    public function testIsatty()
    {
        $stream = new Stream('../temp/testStreamFilterRemove.txt', 'w+');
        $rst = $stream->isatty();
        var_dump($rst);
        self::assertFalse($rst);
    }

    public function testResolveIncludePath()
    {
        $path = Stream::resolveIncludePath('../temp/testStreamFilterRemove.txt');
        var_dump($path);
        self::assertIsString($path);
    }

    public function testSelect()
    {
        $sock1 = $sock2 = $sock3 = fopen('../temp/testStreamFilterRemove.txt', 'w+');
        $sockets = ["sock_1" => $sock1, "sock_2" => $sock2, "sock_3" => $sock3];

        $read = $write = $error = $sockets;
        $num = Stream::select($read, $write, $error, 10);
        var_dump($num);
        self::assertGreaterThan(0, $num);
    }

    public function testSetBlocking()
    {
        $stream = new Stream('https://www.baidu.com', 'r');
        $rst = $stream->setBlocking(0);
        var_dump($rst);
        self::assertTrue($rst);
    }

    public function testSetChunkSize()
    {
        $stream = new Stream('https://www.baidu.com', 'r');
        $rst = $stream->setChunkSize(100);
        var_dump($rst);
        self::assertIsInt($rst);
    }

    public function testSetReadBuffer()
    {
        $stream = new Stream('https://www.baidu.com', 'r');
        $rst = $stream->setReadBuffer(1024);
        var_dump($rst);
        self::assertIsInt($rst);
    }

    public function testSetTimeout()
    {
        $stream = new Stream('https://www.baidu.com', 'r');
        $rst = $stream->setTimeout(30);
        var_dump($rst);
        self::assertTrue($rst);
    }

    public function testSetWriteBuffer()
    {
        $stream = new Stream('https://www.baidu.com', 'r');
        $rst = $stream->setWriteBuffer(1024);
        var_dump($rst);
        self::assertIsInt($rst);
    }

    /**
     * 该测试为阻断方式，请在命令行中中使用telnet进行效果查看
     */
    public function testSocketAccept()
    {
        $socket = Stream::socketServer("tcp://0.0.0.0:8000", $errno, $errstr);

        if (!$socket) {
            echo "$errstr ($errno)<br />\n";
        } else {
            $stream = new Stream($socket);
            $fpsocket = new File($socket);
            while ($conn = $stream->socketAccept(100)) {
                $fpconn = new File($conn);
                $fpconn->write('The local time is ' . date('Y-m-d H:i:s') . "\n");
                $fpconn->close();
            }
            $fpsocket->close();
        }
    }

    public function testSocketClient()
    {
        $fp = Stream::socketClient("tcp://www.baidu.com:80", $errno, $errstr, 30);
        self::assertIsResource($fp);
        if (!$fp) {
            echo "$errstr ($errno)<br />\n";
        } else {
            $ffp = new File($fp);
            $ffp->write("GET / HTTP/1.0\r\nHost: www.baidu.com\r\nAccept: */*\r\n\r\n");
            while (!$ffp->eof()) {
                echo $ffp->gets(1024);
            }
            $ffp->close();
        }
    }

    /**
     * @todo 未实际测试
     */
    public function testSocketEnableCrypto()
    {
        $fp = Stream::socketClient("tcp://www.baidu.com:80", $errno, $errstr, 30);
        $stream = new Stream($fp);
        $rst = $stream->socketEnableCrypto(true, STREAM_CRYPTO_METHOD_TLSv1_0_SERVER);
        self::assertTrue($rst);
        $rst = $stream->socketEnableCrypto(false);
        self::assertTrue($rst);
    }

    public function testSocketGetName()
    {
        $stream = new Stream('https://www.baidu.com', 'r');
        $rst = $stream->socketGetName(true);
        var_dump($rst);
        $rst = $stream->socketGetName(false);
        var_dump($rst);
        self::assertIsString($rst);
    }

    public function testSocketPair()
    {
        $sockets = Stream::socketPair(STREAM_PF_INET, STREAM_SOCK_STREAM, STREAM_IPPROTO_TCP);
        var_dump($sockets);
        self::assertIsArray($sockets);
    }

    /**
     * 该测试为阻断方式，请在命令行中中使用telnet进行效果查看
     */
    public function testSocketRecvfrom()
    {
        $server = Stream::socketServer("tcp://0.0.0.0:8000", $errno, $errstr);

        if (!$server) {
            echo "$errstr ($errno)<br />\n";
        } else {
            $server = new Stream($server);
            $socket = $server->socketAccept();
            $socket = new Stream($socket);

            /* Grab a packet (1500 is a typical MTU size) of OOB data */
            echo "Received Out-Of-Band: '" . $socket->socketRecvfrom(1500, STREAM_OOB) . "'\n";

            /* Take a peek at the normal in-band data, but don't consume it. */
            echo "Data: '" . $socket->socketRecvfrom(1500, STREAM_PEEK) . "'\n";

            /* Get the exact same packet again, but remove it from the buffer this time. */
            echo "Data: '" . $socket->socketRecvfrom(1500) . "'\n";

            self::assertIsString($socket->socketRecvfrom(1500));
        }
    }

    public function testSocketSendto()
    {
        $socket = Stream::socketClient("tcp://www.baidu.com:80", $errno, $errstr, 30);
        $stream = new Stream($socket);
        $rst = $stream->socketSendto("Out of Band data.", STREAM_OOB);
        var_dump($rst);
        self::assertIsInt($rst);
    }

    public function testSocketServer()
    {
        $socket = Stream::socketServer("tcp://0.0.0.0:8000", $errno, $errstr);
        var_dump($socket);
        self::assertIsResource($socket);
    }

    public function testSocketShutdown()
    {
        $socket = Stream::socketClient("tcp://www.baidu.com:80", $errno, $errstr, 30);
        $stream = new Stream($socket);
        $rst = $stream->socketShutdown(STREAM_SHUT_WR);
        var_dump($rst);
        self::assertTrue($rst);
    }

    public function testSupportsLock()
    {
        $socket = Stream::socketServer("tcp://0.0.0.0:8000", $errno, $errstr);
        $stream = new Stream($socket);
        $rst = $stream->supportsLock();
        var_dump($rst);
        self::assertFalse($rst);
    }

    public function testWrapperRegister()
    {
        $rst = Stream::wrapperRegister('txt', 'TxtStreamWrapper');
        var_dump($rst);
        self::assertTrue($rst);
    }

    public function testWrapperRestore()
    {
        $existed = in_array("http", Stream::getWrappers());
        if ($existed) {
            Stream::wrapperUnregister("http");
        }
        $rst = Stream::wrapperRegister("http", "TxtStreamWrapper");
        self::assertTrue($rst);
        $myvar = "";

        $fp = fopen("http://myvar", "r+");

        fwrite($fp, "line1\n");
        fwrite($fp, "line2\n");
        fwrite($fp, "line3\n");

        rewind($fp);
        while (!feof($fp)) {
            echo fgets($fp);
        }
        fclose($fp);
        var_dump($myvar);

        $existed = in_array("http", Stream::getWrappers());
        if ($existed) {
            $rst = Stream::wrapperRestore("http");
            var_dump($rst);
            self::assertTrue($rst);
        }
        $rst = Stream::wrapperRestore("http");
        var_dump($rst);
        self::assertTrue($rst);
    }

    public function testWrapperUnregister()
    {
        Stream::wrapperRegister("var", "TxtStreamWrapper");
        $rst = Stream::wrapperUnregister("var");
        var_dump($rst);
        self::assertTrue($rst);
    }
}

/**
 * 自定义过滤器
 *
 * 该过滤器功能为使所有字符串改为大写
 */
class strtoupper_filter extends php_user_filter
{
    function filter($in, $out, &$consumed, $closing)
    {
        while ($bucket = stream_bucket_make_writeable($in)) {
            $bucket->data = strtoupper($bucket->data);
            $consumed += $bucket->datalen;
            stream_bucket_append($out, $bucket);
        }
        return PSFS_PASS_ON;
    }
}

/**
 * 自定义封装协议
 */
class TxtStreamWrapper
{
    const WRAPPER_NAME = 'callback';

    public $context;

    private $_cb;

    private $seek = 0;

    private $_eof = false;

    private static $_isRegistered = false;

    public static function getContext($cb)
    {
        if (!self::$_isRegistered) {
            stream_wrapper_register(self::WRAPPER_NAME, get_class());
            self::$_isRegistered = true;
        }
        if (!is_callable($cb)) return false;
        return stream_context_create([self::WRAPPER_NAME => ['cb' => $cb]]);
    }

    public function stream_open($path, $mode, $options, &$opened_path)
    {
        return true;
    }

    public function stream_read($count)
    {
        $this->seek = $this->seek + $count;
        if($this->seek > 10 ) {
            $this->_eof = true;
        }
        return (string)$this->seek;
    }

    public function stream_write($data)
    {
        return strlen($data);
    }

    public function stream_seek($offset, $whence  = 0)
    {
        return true;
    }

    public function stream_tell()
    {
        return 1;
    }

    public function stream_eof()
    {
        return $this->_eof;
    }
}
