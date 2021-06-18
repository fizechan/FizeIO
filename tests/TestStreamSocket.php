<?php

use fize\io\FFile;
use fize\io\StreamSocket;
use PHPUnit\Framework\TestCase;

class TestStreamSocket extends TestCase
{

    /**
     * @todo 未实际测试
     */
    public function testEnableCrypto()
    {
        $client = StreamSocket::client("tcp://www.baidu.com:80", $errno, $errstr, 30);
        if (!$client) {
            echo "$errstr ($errno)<br />\n";
            return;
        }
        $socket = new StreamSocket($client);
        $rst = $socket->enableCrypto(true, STREAM_CRYPTO_METHOD_TLSv1_0_SERVER);
        self::assertTrue($rst);
        $rst = $socket->enableCrypto(false);
        self::assertTrue($rst);
    }

    public function testGetName()
    {
        $ff = FFile::open('https://www.baidu.com', 'r');
        $socket = new StreamSocket($ff);
        $rst = $socket->getName(true);
        var_dump($rst);
        $rst = $socket->getName(false);
        var_dump($rst);
        self::assertIsString($rst);
    }

    public function testPair()
    {
        $sockets = StreamSocket::pair(STREAM_PF_INET, STREAM_SOCK_STREAM, STREAM_IPPROTO_TCP);
        var_dump($sockets);
        self::assertIsArray($sockets);
    }

    /**
     * 该测试为阻断方式，请在命令行中中使用telnet进行效果查看
     */
    public function testRecvfrom()
    {
        $server = StreamSocket::server("tcp://0.0.0.0:8000", $errno, $errstr);
        if (!$server) {
            echo "$errstr ($errno)<br />\n";
            return;
        }

        $socket = StreamSocket::accept($server);
        $socket = new StreamSocket($socket);

        /* Grab a packet (1500 is a typical MTU size) of OOB data */
        echo "Received Out-Of-Band: '" . $socket->recvfrom(1500, STREAM_OOB) . "'\n";

        /* Take a peek at the normal in-band data, but don't consume it. */
        echo "Data: '" . $socket->recvfrom(1500, STREAM_PEEK) . "'\n";

        /* Get the exact same packet again, but remove it from the buffer this time. */
        echo "Data: '" . $socket->recvfrom(1500) . "'\n";

        self::assertIsString($socket->recvfrom(1500));
    }

    public function testSendto()
    {
        $socket = StreamSocket::client("tcp://www.baidu.com:80", $errno, $errstr, 30);
        $stream = new StreamSocket($socket);
        $rst = $stream->sendto("Out of Band data.", STREAM_OOB);
        var_dump($rst);
        self::assertIsInt($rst);
    }

    public function testShutdown()
    {
        $socket = StreamSocket::client("tcp://www.baidu.com:80", $errno, $errstr, 30);
        $stream = new StreamSocket($socket);
        $rst = $stream->shutdown(STREAM_SHUT_WR);
        var_dump($rst);
        self::assertTrue($rst);
    }

    /**
     * 该测试为阻断方式，请在命令行中中使用telnet进行效果查看
     */
    public function testAccept()
    {
        $server = StreamSocket::server("tcp://0.0.0.0:8000", $errno, $errstr);
        if (!$server) {
            echo "$errstr ($errno)<br />\n";
            return;
        }

        while ($conn = StreamSocket::accept($server, 100)) {
            $fpconn = new FFile($conn);
            $fpconn->write('The local time is ' . date('Y-m-d H:i:s') . "\n");
            $fpconn->close();
        }
        unset($socket);
    }

    public function testClient()
    {
        $fp = StreamSocket::client("tcp://www.baidu.com:80", $errno, $errstr, 30);
        var_dump($fp);
        self::assertIsResource($fp);
    }

    public function testSocketServer()
    {
        $socket = StreamSocket::server("tcp://0.0.0.0:8000", $errno, $errstr);
        var_dump($socket);
        self::assertIsResource($socket);
    }
}
