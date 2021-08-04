<?php

use fize\io\FileF;
use fize\io\Stream;
use fize\io\StreamSocket;
use PHPUnit\Framework\TestCase;

class TestStream extends TestCase
{

    public function testCopyToStream()
    {
        $ff = new FileF('https://www.baidu.com', 'r');
        $stream = new Stream($ff);
        $dest = new FileF('../temp/baidu.txt', 'w');
        $rst = $stream->copyToStream($dest);
        var_dump($rst);
        self::assertIsInt($rst);
    }

    public function testGetContents()
    {
        $ff = new FileF('https://www.baidu.com', 'r');
        $stream = new Stream($ff);
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
        $ff = new FileF('https://www.baidu.com', 'r');
        $stream = new Stream($ff);
        $line1 = $stream->getLine(100);
        var_dump($line1);
        self::assertIsString($line1);
        $line2 = $stream->getLine(100, ">");
        var_dump($line2);
        self::assertIsString($line2);
    }

    public function testGetMetaData()
    {
        $ff = new FileF('https://www.baidu.com', 'r');
        $stream = new Stream($ff);
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
        $ff = new FileF('../temp/testStreamFilterRemove.txt', 'w+');
        $stream = new Stream($ff);
        $rst1 = $stream->isLocal();
        var_dump($rst1);
        self::assertTrue($rst1);
        $rst2 = $stream->isLocal('https://www.baidu.com');
        var_dump($rst2);
        self::assertFalse($rst2);
    }

    public function testIsatty()
    {
        $ff = new FileF('../temp/testStreamFilterRemove.txt', 'w+');
        $stream = new Stream($ff);
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
        $sock1 = $sock2 = $sock3 = new FileF('../temp/testStreamFilterRemove.txt', 'w+');
        $sockets = ["sock_1" => $sock1, "sock_2" => $sock2, "sock_3" => $sock3];

        $read = $write = $error = $sockets;
        $num = Stream::select($read, $write, $error, 10);
        var_dump($num);
        self::assertGreaterThan(0, $num);
    }

    public function testSetBlocking()
    {
        $ff = new FileF('https://www.baidu.com', 'r');
        $stream = new Stream($ff);
        $rst = $stream->setBlocking(0);
        var_dump($rst);
        self::assertTrue($rst);
    }

    public function testSetChunkSize()
    {
        $ff = new FileF('https://www.baidu.com', 'r');
        $stream = new Stream($ff);
        $rst = $stream->setChunkSize(100);
        var_dump($rst);
        self::assertIsInt($rst);
    }

    public function testSetReadBuffer()
    {
        $ff = new FileF('https://www.baidu.com', 'r');
        $stream = new Stream($ff);
        $rst = $stream->setReadBuffer(1024);
        var_dump($rst);
        self::assertIsInt($rst);
    }

    public function testSetTimeout()
    {
        $ff = new FileF('https://www.baidu.com', 'r');
        $stream = new Stream($ff);
        $rst = $stream->setTimeout(30);
        var_dump($rst);
        self::assertTrue($rst);
    }

    public function testSetWriteBuffer()
    {
        $ff = new FileF('https://www.baidu.com', 'r');
        $stream = new Stream($ff);
        $rst = $stream->setWriteBuffer(1024);
        var_dump($rst);
        self::assertIsInt($rst);
    }

    public function testSupportsLock()
    {
        $socket = StreamSocket::server("tcp://0.0.0.0:8000", $errno, $errstr);
        $stream = new Stream($socket);
        $rst = $stream->supportsLock();
        var_dump($rst);
        self::assertFalse($rst);
    }
}
