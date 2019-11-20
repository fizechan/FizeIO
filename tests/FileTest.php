<?php


use fize\io\File;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{

    public function test__construct()
    {
        $file = new File('../temp/test.txt', 'r+');
        $file->open('w');
        $len = $file->fwrite("1234567890\r\n");
        $file->close();
        self::assertGreaterThan(0, $len);
    }

    public function testChgrp()
    {
        $group = filegroup('../temp/test.txt');
        var_dump($group);
        $file = new File('../temp/test.txt', 'r');
        $result = $file->chgrp(0);
        var_dump($result);
        self::assertTrue($result);
    }

    public function testChmod()
    {
        $file = new File('../temp/test.txt', 'r');
        $result = $file->chmod(0777);
        var_dump($result);
        self::assertTrue($result);
    }

    public function testChown()
    {
        $ower = fileowner('../temp/test.txt');
        var_dump($ower);
        $file = new File('../temp/test.txt', 'r');
        $result = $file->chown('cfz87');
        var_dump($result);
        self::assertTrue($result);
    }

    public function testClearstatcache()
    {
        $file = new File('../temp/test.txt', 'a+');
        $file->fwrite("123456");
        $size1 = $file->getSize();
        $file->fwrite("789000");
        $size2 = $file->getSize();
        self::assertEquals($size1, $size2);
        $file->clearstatcache();
        $size3 = $file->getSize();
        self::assertNotEquals($size2, $size3);
        $file->close();
    }

    public function testCopy()
    {
        $file = new File('../temp/test.txt', 'a+');
        $result1 = $file->copy('../temp/temp2');
        var_dump($result1);
        self::assertTrue($result1);
        $result1 = $file->copy('../temp', 'test2.txt');
        var_dump($result1);
        self::assertTrue($result1);
        $result1 = $file->copy('../temp', 'test2.txt', true);
        var_dump($result1);
        self::assertTrue($result1);
    }

    public function testDelete()
    {

    }

    public function testTmpfile()
    {
        $resource = File::tmpfile();
        var_dump($resource);
        self::assertIsResource($resource);

        fwrite($resource, "这是一些测试字符串");
        fseek($resource, 0);
        $string = fread($resource, 1024);
        self::assertEquals($string, "这是一些测试字符串");
    }

    public function testTell()
    {
        $file = new File('./data/test.txt', 'r+');
        $file->open('w');
        $file->fwrite("1234567890\r\n");
        $file->close();
        $file->open('r');
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

    public function testGets()
    {
        $file = new File('./data/test.txt');
        $file->open('r');
        $content = $file->gets(1024);
        var_dump($content);
        self::assertEquals(strlen($content), 10);
        $file->close();
    }

    public function testLinkinfo()
    {

    }

    public function testTouch()
    {

    }

    public function testSymlink()
    {

    }

    public function testIsWriteable()
    {

    }

    public function testUmask()
    {

    }

    public function testPassthru()
    {

    }

    public function testReadfile()
    {

    }

    public function testClose()
    {

    }

    public function testGetContentsOnArray()
    {

    }

    public function testPuts()
    {

    }

    public function testRename()
    {

    }





    public function testRead()
    {

    }



    public function testPathinfo()
    {

    }

    public function testIsUploadedFile()
    {

    }

    public function testReadlink()
    {

    }

    public function testRealpathCacheGet()
    {

    }

    public function testExists()
    {

    }

    public function testOpen()
    {

    }

    public function testDirname()
    {

    }

    public function testGetContents()
    {

    }

    public function testGetInfo()
    {

    }

    public function testGetcsv()
    {

    }

    public function testGetc()
    {

    }

    public function testPutcsv()
    {

    }

    public function testFlush()
    {

    }

    public function testLock()
    {

    }



    public function testStat()
    {

    }

    public function testGetss()
    {

    }

    public function testRealpathCacheSize()
    {

    }

    public function testWrite()
    {

    }




    public function testScanf()
    {

    }

    public function testPutContents()
    {

    }

    public function testTruncate()
    {

    }

    public function testNmatch()
    {

    }

    public function testSetBuffer()
    {

    }

    public function testLink()
    {

    }
}
