<?php


use fize\io\FileAbstract;
use fize\io\FileF;
use PHPUnit\Framework\TestCase;

class TestFileAbstract extends TestCase
{

    public function test__construct()
    {
        $ff = new FileF(fopen(dirname(__DIR__) . '/temp/testSet.txt', 'w'));
        $ff->write('测试一下啦');
        $ff->close();
        self::assertInstanceOf(FileAbstract::class, $ff);
    }

    public function testEof()
    {
        $ff = new FileF();
        $ff->open(dirname(__DIR__) . '/temp/testSet.txt', 'r');
        $rst = $ff->eof();
        self::assertFalse($rst);
        $ff->read(100);
        $rst = $ff->eof();
        self::assertTrue($rst);
    }

    public function testFlush()
    {
        $ff = new FileF();
        $ff->open(dirname(__DIR__) . '/temp/testFlush.txt', 'w');
        $ff->write("测试一下啦1\r\n");
        $ff->write("测试一下啦2\r\n");
        $ff->write("测试一下啦3\r\n");
        $rst = $ff->flush();
        self::assertTrue($rst);
        $ff->close();
    }

    public function testGetc()
    {
        $ff = new FileF();
        $ff->open(dirname(__DIR__) . '/temp/testGetc.txt', 'r');
        $c = $ff->getc();
        var_dump($c);
        self::assertEquals('A', $c);
        $ff->read(1000);
        $e = $ff->eof();
        self::assertTrue($e);
        $c = $ff->getc();
        var_dump($c);
        self::assertFalse($c);
    }

    public function testGetss()
    {

    }

    public function testSetBuffer()
    {

    }

    public function testRead()
    {

    }



    public function testWrite()
    {

    }

    public function testSeek()
    {

    }



    public function testPassthru()
    {

    }

    public function testPuts()
    {

    }



    public function testTruncate()
    {

    }

    public function testTell()
    {

    }

    public function testScanf()
    {

    }

    public function testLock()
    {

    }

    public function testRewind()
    {

    }

    public function testGets()
    {

    }
}
