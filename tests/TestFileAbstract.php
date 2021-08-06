<?php

use fize\io\FileAbstract;
use fize\io\FileF;
use fize\io\FileP;
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

    public function testGetStream()
    {
        $ff = new FileF();
        $ff->open(dirname(__DIR__) . '/temp/cfztest.txt', 'r');
        $stream = $ff->getStream();
        var_dump($stream);
        self::assertIsResource($stream);
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
        self::assertEmpty($c);
    }

    public function testGets()
    {
        $file = new FileF();
        $file->open('../temp/testGetc.txt', 'r');
        $content = $file->gets(11);
        var_dump($content);
        self::assertEquals(10, strlen($content));

        $content = $file->gets();
        var_dump($content);

        $file->close();
    }

    public function testGetss()
    {
        $file = new FileF();
        $file->open('../temp/testGetc.txt', 'r');
        $content = $file->getss();
        var_dump($content);
        self::assertEquals('ABCDEFG123456', $content);
        $file->close();
    }

    public function testLock()
    {
        $file = new FileF();
        $file->open('../temp/test.txt', 'w+');
        $rst1 = $file->lock(LOCK_EX);
        self::assertTrue($rst1);
        if($rst1) {
            $file->write("\n这是我要写入的内容1");
            $file->write("\n这是我要写入的内容2");
            $rst2 = $file->lock(LOCK_UN);
            self::assertTrue($rst2);
        }
        $file->close();
    }

    public function testPassthru()
    {
        if (substr(php_uname(), 0, 7) == "Windows"){
            $cmd = "start /B php --version";
        } else {
            $cmd = "bash php --version";  //@todo 待验证
        }
        $file = new FileP();
        $file->open($cmd, 'r');
        $len = $file->passthru();
        $file->close();
        self::assertIsInt($len);
    }

    public function testPuts()
    {
        $file = new FileF();
        $file->open('../temp/test.txt', 'a+');
        $len = $file->puts('这是我要写入的字符串');
        var_dump($len);
        self::assertIsInt($len);
        $file->close();
    }

    public function testRead()
    {
        $file = new FileF();
        $file->open('../temp/test.txt', 'r');
        $content = $file->read(1024);
        var_dump($content);
        self::assertIsString($content);
        $file->close();
    }

    public function testScanf()
    {
        $file = new FileF();
        $file->open('../temp/test.txt', 'r');
        $info = $file->scanf('%s');
        var_dump($info);
        self::assertIsArray($info);
        $file->close();
    }

    public function testSeek()
    {
        $file = new FileF();
        $file->open('../temp/testGetc.txt', 'r');
        $file->gets(10);
        $file->seek(0);
        $content = $file->gets(14);
        var_dump($content);
        self::assertEquals('ABCDEFG123456', $content);
    }

    public function testTell()
    {
        $file = new FileF();
        $file->open('../temp/test.txt', 'w');
        $file->write("1234567890\r\n");
        $file->close();
        $file->open('../temp/test.txt', 'r');
        $cursor1 = $file->tell();
        var_dump($cursor1);
        self::assertEmpty($cursor1);

        $content = $file->gets(10);
        var_dump($content);

        $cursor2 = $file->tell();
        var_dump($cursor2);
        self::assertNotEquals($cursor1, $cursor2);
        $file->close();
    }

    public function testTruncate()
    {
        $file = new FileF();
        $file->open('../temp/test.txt', 'a+');
        $rst = $file->truncate(5);
        self::assertTrue($rst);
        $file->close();
    }

    public function testWrite()
    {
        $file = new FileF();
        $file->open('../temp/test.txt', 'w');
        $len = $file->write("1234567890\r\n");
        $file->close();
        self::assertGreaterThan(0, $len);
    }

    public function testRewind()
    {
        $file = new FileF();
        $file->open('../temp/test.txt', 'r');
        $content1 = $file->gets();
        var_dump($content1);
        $file->rewind();
        $content2 = $file->gets();
        var_dump($content2);
        self::assertEquals($content1, $content2);
        $file->close();
    }

    public function testSetBuffer()
    {
        $fp = fopen('../temp/test.txt', "w");
        $rst = stream_set_write_buffer($fp, 0);
        var_dump($rst);
        self::assertEquals(-1, $rst);
        fclose($fp);

        $file = new FileF();
        $file->open('../temp/test.txt', 'w');
        $rst = $file->setBuffer(512);
        var_dump($rst);
        self::assertEquals(-1, $rst);
        $file->write('123456');
        $file->write('654321');
        $file->rewind();

        $len = $file->write("1234567890\r\n");
        $file->close();
        self::assertGreaterThan(0, $len);
    }
}
