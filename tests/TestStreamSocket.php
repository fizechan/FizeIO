<?php


use fize\io\StreamSocket;
use PHPUnit\Framework\TestCase;

class TestStreamSocket extends TestCase
{

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

}
