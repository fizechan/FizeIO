<?php

use fize\io\File;
use PHPUnit\Framework\TestCase;

class TestFile extends TestCase
{

    public function test__construct()
    {
        $root = dirname(__DIR__);
        $file = new File($root . '/temp/test.txt', 'a');
        $len = $file->fwrite("1234567890\r\n");
        self::assertGreaterThan(0, $len);

        $file1 = new File('php://temp', 'r+');
        var_dump($file1);
    }

    /**
     * @todo 测试未通过
     */
    public function testChgrp()
    {
        $root = dirname(__DIR__);

        $group = filegroup($root . '/temp/test.txt');
        var_dump($group);
        $file = new File($root . '/temp/test.txt', 'r');
        $result = $file->chgrp(0);
        var_dump($result);
        self::assertTrue($result);
    }

    public function testChmod()
    {
        $root = dirname(__DIR__);
        $file = new File($root . '/temp/test.txt');
        $result = $file->chmod(0777);
        var_dump($result);
        self::assertTrue($result);
    }

    /**
     * @todo 测试未通过
     */
    public function testChown()
    {
        $root = dirname(__DIR__);
        $ower = fileowner($root . '/temp/test.txt');
        var_dump($ower);
        $file = new File($root . '/temp/test.txt', 'r');
        $result = $file->chown('cfz87');
        var_dump($result);
        self::assertTrue($result);
    }

    public function testClearstatcache()
    {
        $root = dirname(__DIR__);
        $file = new File($root . '/temp/test.txt', 'a+');
        $file->fwrite("123456");
        $size1 = $file->getSize();
        $file->fwrite("789000");
        $size2 = $file->getSize();
        self::assertEquals($size1, $size2);
        $file->clearstatcache();
        $size3 = $file->getSize();
        self::assertNotEquals($size2, $size3);
    }

    public function testCopy()
    {
        $root = dirname(__DIR__);
        $file = new File($root . '/temp/test.txt');
        $result1 = $file->copy($root . '/temp/temp2');
        var_dump($result1);
        self::assertTrue($result1);
        $result1 = $file->copy($root . '/temp', 'test2.txt');
        var_dump($result1);
        self::assertTrue($result1);
        $result1 = $file->copy($root . '/temp', 'test2.txt', true);
        var_dump($result1);
        self::assertTrue($result1);
    }

    public function testDelete()
    {
        $file = new File(dirname(__DIR__) . '/temp/test3.txt');
        $result = $file->delete();
        var_dump($result);
        self::assertTrue($result);
    }

    public function testGetContents()
    {
        $file = new File(dirname(__DIR__) . '/temp/data/test.html');
        $content = $file->getContents();
        var_dump($content);
        self::assertIsString($content);
    }

    public function testPutContents()
    {
        $file = new File(dirname(__DIR__) . '/temp/data/test.html');
        $len = $file->putContents("\n<h2>很好很强大2</h2>", FILE_APPEND);
        self::assertIsInt($len);
    }

    public function testNmatch()
    {
        $file = new File(dirname(__DIR__) . '/temp/data/test.html');
        $rst = $file->nmatch('*.html');
        var_dump($rst);
        self::assertTrue($rst);
    }

    public function testIsUploadedFile()
    {
        $file = new File(dirname(__DIR__) . '/temp/test.txt');
        $bool = $file->isUploadedFile();
        var_dump($bool);
        self::assertFalse($bool);
    }

    public function testLink()
    {
        $root = dirname(__DIR__);
        $file = new File($root . '/temp/test.txt', 'r');
        $bool = $file->link($root . '/temp/test_link2.txt');
        var_dump($bool);
        self::assertTrue($bool);
    }

    public function testLinkinfo()
    {
        $file = new File(dirname(__DIR__) . '/temp/test_link.txt', 'r');
        $info = $file->linkinfo();
        var_dump($info);
        self::assertGreaterThan(0, $info);
    }

    public function testReadfile()
    {
        $file = new File(dirname(__DIR__) . '/temp/test.txt');
        $rst = $file->readfile();
        var_dump($rst);
        self::assertIsInt($rst);
    }

    public function testReadlink()
    {
        $file = new File(dirname(__DIR__) . '/temp/test_link.txt');
        $rst = $file->readlink();
        var_dump($rst);
        self::assertIsString($rst);
    }

    public function testRename()
    {
        $root = dirname(__DIR__);
        $file = new File($root . '/temp/test2.txt');
        $rst = $file->rename($root . '/temp/test2_new.txt');
        var_dump($rst);
        self::assertTrue($rst);
    }

    /**
     * 需要以管理员权限运行
     */
    public function testSymlink()
    {
        $root = dirname(__DIR__);
        $file = new File($root . '/temp/test.txt');
        $bool = $file->symlink($root . '/temp/test_symlink');
        var_dump($bool);
        self::assertTrue($bool);
    }

    public function testTouch()
    {
        $root = dirname(__DIR__);
        $file = new File($root . '/temp/test3.txt', 'w+');
        $rst = $file->touch();
        var_dump($rst);
        self::assertTrue($rst);
    }

    public function testUnlink()
    {
        $file = new File(dirname(__DIR__) . '/temp/test3.txt');
        $result = $file->unlink();
        var_dump($result);
        self::assertTrue($result);
    }

    public function testgetMime()
    {
        $file = new File(dirname(__DIR__) . '/temp/test_docx');
        $mime = $file->getMime();
        var_dump($mime);
        self::assertIsString($mime);
    }

    public function testgetExtensionPossible()
    {
        $file = new File(dirname(__DIR__) . '/temp/test_docx');
        $ext = $file->getExtensionPossible();
        var_dump($ext);
        self::assertIsString($ext);
    }

    public function testExists()
    {
        $root = dirname(__DIR__);
        $rst1 = File::exists($root . '/temp/data/test.html');
        self::assertTrue($rst1);
        $rst1 = File::exists($root . '/temp/data/Test.html');
        self::assertFalse($rst1);
        $rst1 = File::exists($root . '/temp/Data/test.html');
        self::assertFalse($rst1);
        $rst2 = File::exists($root . '/temp/data/file_not_exists.txt');
        self::assertFalse($rst2);
    }

    public function testRealpath()
    {
        $realpath = File::realpath(dirname(__DIR__) . '/temp/test.txt');
        var_dump($realpath);

        $realpath = File::realpath(dirname(__DIR__) . '/temp/dir_not_exists/dir_not_exists2/file_not_exists.txt', false);
        var_dump($realpath);

        self::assertIsString($realpath);
    }

    public function testGetExtensionFromMime()
    {
        $ext = File::getExtensionFromMime('application/pdf');
        self::assertEquals('pdf', $ext);
    }
}
