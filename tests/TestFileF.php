<?php

namespace Tests;

use Fize\IO\FileF;
use PHPUnit\Framework\TestCase;

class TestFileF extends TestCase
{

    public function test__destruct()
    {
        $file1 = new FileF();
        $file1->open(__DIR__ . '/../temp/test.txt', 'w+');
        unset($file1);

        $file = new FileF();
        $file->open(__DIR__ . '/../temp/test.txt', 'w+');
        self::assertTrue(true);
    }

    public function testClose()
    {
        $file = new FileF();
        $file->open(__DIR__ . '/../temp/test.txt', 'w+');
        $rst = $file->close();
        self::assertTrue($rst);
    }

    public function testGetcsv()
    {
        $file = new FileF();
        $file->open(__DIR__ . '/../temp/data/test.csv', 'r');
        $csv = $file->getcsv();
        var_dump($csv);
        self::assertIsArray($csv);
    }

    public function testPutcsv()
    {
        $file = new FileF();
        $file->open(__DIR__ . '/../temp/csv.csv', 'w+');
        $fields = ['汽车', 'VIN码查询', 'Vin/query', '根据VIN码查询车辆相关信息', '聚合', '通过'];
        $len = $file->putcsv($fields);
        var_dump($len);
        self::assertIsInt($len);
        $file->close();
    }

    public function testOpen()
    {
        $file = new FileF();
        $file->open(__DIR__ . '/../temp/test.txt', 'w+');
        self::assertTrue(true);
    }

    public function test__construct()
    {
        $ff = new FileF(fopen(__DIR__ . '/../temp/testFileFConstruct.txt', 'w'));
        $ff->write('test');
        $ff->close();
        self::assertNotNull($ff);
    }

    public function testGetStream()
    {
        $file = new FileF();
        $file->open(__DIR__ . '/../temp/test.txt', 'r');
        $stream = $file->getStream();
        var_dump($stream);
        self::assertIsResource($stream);
        $file->close();
    }

    public function testEof()
    {
        $file = new FileF();
        $file->open(__DIR__ . '/../temp/test.txt', 'r');
        $rst = $file->eof();
        self::assertFalse($rst);
        $file->read(100);
        $rst = $file->eof();
        self::assertTrue($rst);
        $file->close();
    }

    public function testFlush()
    {
        $file = new FileF();
        $file->open(__DIR__ . '/../temp/testFileFFlush.txt', 'w');
        $file->write("test1\r\n");
        $file->write("test2\r\n");
        $rst = $file->flush();
        self::assertTrue($rst);
        $file->close();
    }

    public function testGetc()
    {
        $file = new FileF();
        $file->open(__DIR__ . '/../temp/testGetc.txt', 'w');
        $file->write('ABCDEFG');
        $file->close();
        $file->open(__DIR__ . '/../temp/testGetc.txt', 'r');
        $c = $file->getc();
        var_dump($c);
        self::assertIsString($c);
        $file->close();
    }

    public function testGets()
    {
        $file = new FileF();
        $file->open(__DIR__ . '/../temp/testGets.txt', 'w');
        $file->write("line1\nline2\n");
        $file->close();
        $file->open(__DIR__ . '/../temp/testGets.txt', 'r');
        $content = $file->gets(11);
        var_dump($content);
        self::assertIsString($content);
        $content = $file->gets();
        var_dump($content);
        $file->close();
    }

    public function testGetss()
    {
        $file = new FileF();
        $file->open(__DIR__ . '/../temp/testGetss.txt', 'w');
        $file->write("<html><body><p>test</p></body></html>");
        $file->close();
        $file->open(__DIR__ . '/../temp/testGetss.txt', 'r');
        $content = $file->getss();
        var_dump($content);
        self::assertIsString($content);
        $file->close();
    }

    public function testLock()
    {
        $file = new FileF();
        $file->open(__DIR__ . '/../temp/test.txt', 'w+');
        $rst1 = $file->lock(LOCK_EX);
        self::assertTrue($rst1);
        $file->write("\ntest content");
        $rst2 = $file->lock(LOCK_UN);
        self::assertTrue($rst2);
        $file->close();
    }

    public function testPassthru()
    {
        $file = new FileF();
        $file->open(__DIR__ . '/../temp/test.txt', 'r');
        $len = $file->passthru();
        $file->close();
        self::assertIsInt($len);
    }

    public function testPuts()
    {
        $file = new FileF();
        $file->open(__DIR__ . '/../temp/test.txt', 'a+');
        $len = $file->puts('test content');
        var_dump($len);
        self::assertIsInt($len);
        $file->close();
    }

    public function testRead()
    {
        $file = new FileF();
        $file->open(__DIR__ . '/../temp/test.txt', 'r');
        $content = $file->read(1024);
        var_dump($content);
        self::assertIsString($content);
        $file->close();
    }

    public function testScanf()
    {
        $file = new FileF();
        $file->open(__DIR__ . '/../temp/test.txt', 'r');
        $info = $file->scanf('%s');
        var_dump($info);
        self::assertTrue(is_array($info) || $info === null);
        $file->close();
    }

    public function testSeek()
    {
        $file = new FileF();
        $file->open(__DIR__ . '/../temp/test.txt', 'r');
        $file->gets(10);
        $file->seek(0);
        $content = $file->gets(14);
        var_dump($content);
        self::assertIsString($content);
        $file->close();
    }

    public function testTell()
    {
        $file = new FileF();
        $file->open(__DIR__ . '/../temp/test.txt', 'w');
        $file->write("1234567890\r\n");
        $file->close();
        $file->open(__DIR__ . '/../temp/test.txt', 'r');
        $cursor1 = $file->tell();
        var_dump($cursor1);
        self::assertEquals(0, $cursor1);
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
        $file->open(__DIR__ . '/../temp/test.txt', 'a+');
        $rst = $file->truncate(5);
        self::assertTrue($rst);
        $file->close();
    }

    public function testWrite()
    {
        $file = new FileF();
        $file->open(__DIR__ . '/../temp/test.txt', 'w');
        $len = $file->write("1234567890\r\n");
        $file->close();
        self::assertGreaterThan(0, $len);
    }

    public function testRewind()
    {
        $file = new FileF();
        $file->open(__DIR__ . '/../temp/test.txt', 'r');
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
        $file = new FileF();
        $file->open(__DIR__ . '/../temp/test.txt', 'w');
        $rst = $file->setBuffer(512);
        var_dump($rst);
        self::assertEquals(-1, $rst);
        $file->write('123456');
        $file->close();
    }
}
