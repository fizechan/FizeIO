<?php

use fize\io\Stream;
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



    public function testSupportsLock()
    {
        $socket = Stream::socketServer("tcp://0.0.0.0:8000", $errno, $errstr);
        $stream = new Stream($socket);
        $rst = $stream->supportsLock();
        var_dump($rst);
        self::assertFalse($rst);
    }
}
