<?php


use fize\io\FileAbstract;
use fize\io\FileF;
use PHPUnit\Framework\TestCase;

class TestFileAbstract extends TestCase
{

    public function testSet()
    {
        $ff = new FileF();
        $ff->set(fopen(dirname(__DIR__) . '/temp/testSet.txt', 'w'));
        $ff->write('测试一下啦');
        $ff->close();
        self::assertInstanceOf(FileAbstract::class, $ff);
    }

    public function testEof()
    {
        $ff = new FileF(dirname(__DIR__) . '/temp/testSet.txt', 'r');
        $rst = $ff->eof();
        self::assertFalse($rst);
        $ff->read(100);
        $rst = $ff->eof();
        self::assertTrue($rst);
    }

    public function testFlush()
    {

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

    public function testGetc()
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
