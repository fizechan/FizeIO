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

    /**
     * @todo 测试未通过
     */
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

    /**
     * @todo 测试未通过
     */
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
        $file = new File('../temp/test.txt');
        $result = $file->delete();
        var_dump($result);
        self::assertTrue($result);
    }

    public function testDirname()
    {
        $file = new File('../temp/test.txt', 'w+');
        $dir = $file->dirname();
        var_dump($dir);
        self::assertIsString($dir);
    }

    public function testClose()
    {
        $file = new File('../temp/test.txt', 'r+');
        $file->open('w');
        $file->fwrite("1234567890\r\n");
        $result = $file->close();
        self::assertTrue($result);
    }

    public function testFlush()
    {
        $file = new File('../temp/test.txt');
        $file->open('w');
        $file->write("1234567890\r\n");
        $file->write("1234567890\r\n");
        $file->write("1234567890\r\n");
        $result = $file->flush();
        $file->close();
        self::assertTrue($result);
    }

    public function testGetc()
    {
        $file = new File('../temp/test.txt');
        $file->open();
        $char = $file->getc();
        var_dump($char);
        self::assertIsString($char);
        $file->close();
    }

    public function testGetcsv()
    {
        $file = new File('../temp/data/test.csv');
        $file->open();
        $csv = $file->getcsv();
        var_dump($csv);
        self::assertIsArray($csv);
        $file->close();
    }

    public function testGets()
    {
        $file = new File('../temp/test.txt');
        $file->open('r');
        $content = $file->gets(11);
        var_dump($content);
        self::assertEquals(strlen($content), 10);
        $file->close();
    }

    /**
     * @todo 测试未通过
     */
    public function testGetss()
    {
        $file = new File('../temp/data/test.html');
        $file->open('r');
        $content = $file->getss();
        var_dump($content);
        self::assertEquals($content, '我是中国人');
        $file->close();
    }

    public function testExists()
    {
        $rst1 = File::exists('../temp/data/test.html');
        self::assertTrue($rst1);
        $rst2 = File::exists('../temp/data/file_not_exists.txt');
        self::assertFalse($rst2);
    }

    public function testGetContents()
    {
        $file = new File('../temp/data/test.html');
        $content = $file->getContents();
        var_dump($content);
        self::assertIsString($content);
    }

    public function testPutContents()
    {
        $file = new File('../temp/data/test.html');
        $len = $file->putContents("\n<h2>很好很强大</h2>", FILE_APPEND);
        self::assertIsInt($len);
    }

    public function testGetContentsArray()
    {
        $file = new File('../temp/data/test.html');
        $lines = $file->getContentsArray();
        var_dump($lines);
        self::assertIsArray($lines);
    }

    public function testGetInfo()
    {
        $file = new File('../temp/data/test.html');
        $atime = $file->getInfo('atime');
        var_dump($atime);
        self::assertIsInt($atime);
        $ctime = $file->getInfo('ctime');
        var_dump($ctime);
        self::assertIsInt($ctime);
        $group = $file->getInfo('group');
        var_dump($group);
        self::assertIsInt($group);
        $inode = $file->getInfo('inode');
        var_dump($inode);
        self::assertIsInt($inode);
        $mtime = $file->getInfo('mtime');
        var_dump($mtime);
        self::assertIsInt($mtime);
        $owner = $file->getInfo('owner');
        var_dump($owner);
        self::assertIsInt($owner);
        $perms = $file->getInfo('perms');
        var_dump($perms);
        self::assertIsInt($perms);
        $size = $file->getInfo('size');
        var_dump($size);
        self::assertIsInt($size);
        $type = $file->getInfo('type');
        var_dump($type);
        self::assertEquals($type, 'file');
    }

    public function testLock()
    {
        $file = new File('../temp/test.txt');
        $file->open('w+');
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

    public function testNmatch()
    {
        $file = new File('../temp/data/test.html');
        $rst = $file->nmatch('*.html');
        var_dump($rst);
        self::assertTrue($rst);
    }

    /**
     * @todo Linux测试条件不一样，待测试
     */
    public function testOpen()
    {
        $file = new File('../temp/test.txt', 'r+');
        $file->open('w');
        $len = $file->write("1234567890\r\n");
        $file->close();
        self::assertGreaterThan(0, $len);

        if (substr(php_uname(), 0, 7) == "Windows"){
            $cmd = "start /B php --version";
        } else {
            $cmd = "bash php --version";
        }
        $file->open('r', true, $cmd);
        $content = $file->gets();
        var_dump($content);
        $file->close();
    }

    public function testPassthru()
    {
        $file = new File('../temp/test.txt');  //必须指定文件，待优化。

        if (substr(php_uname(), 0, 7) == "Windows"){
            $cmd = "start /B php --version";
        } else {
            $cmd = "bash php --version";  //@todo 待验证
        }
        $file->open('r', true, $cmd);
        $len = $file->passthru();
        $file->close();
        self::assertIsInt($len);
    }

    public function testPutcsv()
    {
        $file = new File('../temp/data/test.csv');
        $file->open('a+');
        $fields = ['汽车', 'VIN码查询', 'Vin/query', '根据VIN码查询车辆相关信息', '聚合', '通过'];
        $len = $file->putcsv($fields);
        var_dump($len);
        self::assertIsInt($len);
        $file->close();
    }

    public function testPuts()
    {
        $file = new File('../temp/test.txt');
        $file->open('a+');
        $len = $file->puts('这是我要写入的字符串');
        var_dump($len);
        self::assertIsInt($len);
        $file->close();
    }

    public function testRead()
    {
        $file = new File('../temp/test.txt');
        $file->open('r');
        $content = $file->read(1024);
        var_dump($content);
        self::assertIsString($content);
        $file->close();
    }

    public function testScanf()
    {
        $file = new File('../temp/test.txt');
        $file->open('r');
        $info = $file->scanf('%s');
        var_dump($info);
        self::assertIsArray($info);
        $file->close();
    }

    public function testStat()
    {
        $file = new File('../temp/test.txt');
        $file->open('r');
        $stat = $file->stat();
        var_dump($stat);
        self::assertIsArray($stat);
    }

    public function testTell()
    {
        $file = new File('../temp/test.txt', 'r+');
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

    public function testTruncate()
    {
        $file = new File('../temp/test.txt', 'r+');
        $file->open('a+');
        $rst = $file->truncate(5);
        self::assertTrue($rst);
        $file->close();
    }

    public function testWrite()
    {
        $file = new File('../temp/test.txt', 'r+');
        $file->open('w');
        $len = $file->write("1234567890\r\n");
        $file->close();
        self::assertGreaterThan(0, $len);
    }

    public function testIsUploadedFile()
    {
        $file = new File('../temp/test.txt', 'r+');
        $bool = $file->isUploadedFile();
        var_dump($bool);
        self::assertFalse($bool);
    }

    public function testIsWriteable()
    {
        $file = new File('../temp/test.txt', 'r');
        $bool = $file->isWriteable();
        var_dump($bool);
        self::assertTrue($bool);
    }

    public function testLink()
    {
        $file = new File('../temp/test.txt', 'r');
        $bool = $file->link('../temp/test_link.txt');
        var_dump($bool);
        self::assertTrue($bool);
    }

    public function testLinkinfo()
    {
        $file = new File('../temp/test_link.txt', 'r');
        $info = $file->linkinfo();
        var_dump($info);
        self::assertGreaterThan(0, $info);
    }

    public function testPathinfo()
    {
        $file = new File('../temp/test.txt', 'r');
        $info = $file->pathinfo();
        var_dump($info);
        self::assertIsArray($info);

        $dir = $file->pathinfo(PATHINFO_DIRNAME);
        var_dump($dir);
        self::assertIsString($dir);
    }

    public function testReadfile()
    {
        $file = new File('../temp/test.txt', 'r');
        $rst = $file->readfile();
        var_dump($rst);
        self::assertIsInt($rst);
    }

    public function testReadlink()
    {
        $file = new File('../temp/test_link.txt', 'r');
        $rst = $file->readlink();
        var_dump($rst);
        self::assertIsString($rst);
    }

    public function testRealpathCacheGet()
    {
        $info = File::realpathCacheGet();
        var_dump($info);
        self::assertIsArray($info);
    }

    public function testRealpathCacheSize()
    {
        $size = File::realpathCacheSize();
        var_dump($size);
        self::assertIsInt($size);
    }

    public function testRename()
    {
        $file = new File('../temp/test2.txt');
        $rst = $file->rename('../temp/test2_new.txt');
        var_dump($rst);
        self::assertTrue($rst);
    }

    /**
     * @todo 测试未通过
     */
    public function testSymlink()
    {
        $file = new File('../temp/test.txt', 'w');
        $bool = $file->symlink('F:/git/github/fize/FizeIo/temp/test_symlink');
        var_dump($bool);
        self::assertTrue($bool);
    }

    public function testTouch()
    {
        $file = new File('../temp/test3.txt', 'w+');
        $rst = $file->touch();
        var_dump($rst);
        self::assertTrue($rst);
    }

    public function testUmask()
    {
        $old = File::umask(0);
        self::assertIsInt($old);
        File::umask($old);

        // Checking
        if ($old != umask()) {
            die('An error occured while changing back the umask');
        }
    }

    /**
     * @todo 测试未通过
     */
    public function testSetBuffer()
    {
        $file = new File('../temp/test.txt', 'r+');
        $file->open('w');
        $rst = $file->setBuffer(512);
        var_dump($rst);
        self::assertEquals(0, $rst);

        $len = $file->write("1234567890\r\n");
        $file->close();
        self::assertGreaterThan(0, $len);
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
}
