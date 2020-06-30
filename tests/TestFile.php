<?php

use fize\io\File;
use fize\io\Directory;
use PHPUnit\Framework\TestCase;

class TestFile extends TestCase
{

    public function test__construct()
    {
        $file = new File('../temp/test.txt', 'w');
        $file->open();
        $len = $file->write("1234567890\r\n");
        $file->close();
        self::assertGreaterThan(0, $len);

        $file1 = new File('php://temp', 'r+');
        var_dump($file1);
    }

    public function test__destruct()
    {
        $file1 = new File(__DIR__ . '/../temp/stream.txt', 'w+');
        $file1->open();
        unset($file1);

        $file = new File(__DIR__ . '/../temp/stream.txt', 'w+');
        $file->open();
        self::assertTrue(true);
    }

    public function testGetSplFileObject()
    {
        $file = new File('../../temp/test.txt', 'w');
        $spl = $file->getSplFileObject();
        var_dump($spl);
        self::assertInstanceOf(SplFileObject::class, $spl);
    }

    public function testBasename()
    {
        $file = new File('../../temp/test.txt');
        $basename = $file->basename();
        var_dump($basename);
        self::assertEquals('test.txt', $basename);
    }

    /**
     * @todo 测试未通过
     */
    public function testChgrp()
    {
        $group = filegroup('../../temp/test.txt');
        var_dump($group);
        $file = new File('../../temp/test.txt', 'r');
        $result = $file->chgrp(0);
        var_dump($result);
        self::assertTrue($result);
    }

    public function testChmod()
    {
        $file = new File('../../temp/test.txt');
        $result = $file->chmod(0777);
        var_dump($result);
        self::assertTrue($result);
    }

    /**
     * @todo 测试未通过
     */
    public function testChown()
    {
        $ower = fileowner('../../temp/test.txt');
        var_dump($ower);
        $file = new File('../../temp/test.txt', 'r');
        $result = $file->chown('cfz87');
        var_dump($result);
        self::assertTrue($result);
    }

    public function testClearstatcache()
    {
        $file = new File('../../temp/test.txt', 'a+');
        $file->open();
        $file->write("123456");
        $size1 = $file->size();
        $file->write("789000");
        $size2 = $file->size();
        self::assertEquals($size1, $size2);
        $file->clearstatcache();
        $size3 = $file->size();
        self::assertNotEquals($size2, $size3);
        $file->close();
    }

    public function testCopy()
    {
        $file = new File('../../temp/test.txt');
        $result1 = $file->copy('../../temp/temp2');
        var_dump($result1);
        self::assertTrue($result1);
        $result1 = $file->copy('../../temp', 'test2.txt');
        var_dump($result1);
        self::assertTrue($result1);
        $result1 = $file->copy('../../temp', 'test2.txt', true);
        var_dump($result1);
        self::assertTrue($result1);
    }

    public function testDelete()
    {
        $file = new File('../../temp/test.txt');
        $result = $file->delete();
        var_dump($result);
        self::assertTrue($result);
    }

    public function testDirname()
    {
        $file = new File('../../temp/test.txt');
        $dir = $file->dirname();
        var_dump($dir);
        self::assertIsString($dir);
    }

    public function testClose()
    {
        $file = new File('../../temp/test.txt', 'w');
        $file->open();
        $file->write("1234567890\r\n");
        $result = $file->close();
        self::assertTrue($result);
    }

    public function testEof()
    {
        $file = new File('../../temp/test.txt', 'r');
        $file->open();
        $rst = $file->eof();
        var_dump($rst);
        self::assertFalse($rst);
        $file->read(1024);
        $rst = $file->eof();
        var_dump($rst);
        self::assertTrue($rst);
    }

    public function testFlush()
    {
        $file = new File('../../temp/test.txt', 'w');
        $file->open();
        $file->write("1234567890\r\n");
        $file->write("1234567890\r\n");
        $file->write("1234567890\r\n");
        $result = $file->flush();
        $file->close();
        self::assertTrue($result);
    }

    public function testGetc()
    {
        $file = new File('../../temp/test.txt', 'r');
        $file->open();
        $char = $file->getc();
        var_dump($char);
        self::assertIsString($char);
        $file->close();
    }

    public function testGetcsv()
    {
        $file = new File('../temp/data/test.csv', 'r');
        $file->open();
        $csv = $file->getcsv();
        var_dump($csv);
        self::assertIsArray($csv);
        $file->close();
    }

    public function testGets()
    {
        $file = new File('../temp/test.txt', 'r');
        $file->open();
        $content = $file->gets(11);
        var_dump($content);
        self::assertEquals(10, strlen($content));
        $file->close();
    }

    public function testGetss()
    {
        $file = new File('../temp/data/test.html', 'r');
        $file->open();
        $content = $file->getss();
        var_dump($content);
        self::assertEquals('我是中国人', $content);
        $file->close();
    }

    public function testExists()
    {
        $rst1 = File::exists('../temp/data/test.html');
        self::assertTrue($rst1);
        $rst1 = File::exists('../temp/data/Test.html');
        self::assertFalse($rst1);
        $rst1 = File::exists('../temp/Data/test.html');
        self::assertFalse($rst1);
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

    public function testFile_()
    {
        $file = new File('../temp/data/test.html');
        $lines = $file->file();
        var_dump($lines);
        self::assertIsArray($lines);
    }

    public function testAtime()
    {
        $file = new File('../temp/data/test.html');
        $atime = $file->atime();
        var_dump($atime);
        self::assertIsInt($atime);
    }

    public function testCtime()
    {
        $file = new File('../temp/data/test.html');
        $ctime = $file->ctime();
        var_dump($ctime);
        self::assertIsInt($ctime);
    }

    public function testGroup()
    {
        $file = new File('../temp/data/test.html');
        $group = $file->group();
        var_dump($group);
        self::assertIsInt($group);
    }

    public function testInode()
    {
        $file = new File('../temp/data/test.html');
        $inode = $file->inode();
        var_dump($inode);
        self::assertIsInt($inode);
    }

    public function testMtime()
    {
        $file = new File('../temp/data/test.html');
        $mtime = $file->mtime();
        var_dump($mtime);
        self::assertIsInt($mtime);
    }

    public function testOwner()
    {
        $file = new File('../temp/data/test.html');
        $owner = $file->owner();
        var_dump($owner);
        self::assertIsInt($owner);
    }

    public function testPerms()
    {
        $file = new File('../temp/data/test.html');
        $perms = $file->perms();
        var_dump($perms);
        self::assertIsInt($perms);
    }

    public function testSize()
    {
        $file = new File('../temp/data/test.html');
        $size = $file->size();
        var_dump($size);
        self::assertIsInt($size);
    }

    public function testType()
    {
        $file = new File('../temp/data/test.html');
        $type = $file->type();
        var_dump($type);
        self::assertEquals('file', $type);
    }

    public function testLock()
    {
        $file = new File('../temp/test.txt', 'w+');
        $file->open();
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

    public function testOpen()
    {
        $file = new File('../temp/test.txt', 'w');
        $file->open();
        $len = $file->write("1234567890\r\n");
        $file->close();
        self::assertGreaterThan(0, $len);
    }

    public function testPassthru()
    {
        $file = new File();

        if (substr(php_uname(), 0, 7) == "Windows"){
            $cmd = "start /B php --version";
        } else {
            $cmd = "bash php --version";  //@todo 待验证
        }
        $file->popen($cmd, 'r');
        $len = $file->passthru();
        $file->close();
        self::assertIsInt($len);
    }

    public function testPutcsv()
    {
        $file = new File('../temp/data/test.csv', 'a+');
        $file->open();
        $fields = ['汽车', 'VIN码查询', 'Vin/query', '根据VIN码查询车辆相关信息', '聚合', '通过'];
        $len = $file->putcsv($fields);
        var_dump($len);
        self::assertIsInt($len);
        $file->close();
    }

    public function testPuts()
    {
        $file = new File('../temp/test.txt', 'a+');
        $file->open();
        $len = $file->puts('这是我要写入的字符串');
        var_dump($len);
        self::assertIsInt($len);
        $file->close();
    }

    public function testRead()
    {
        $file = new File('../temp/test.txt', 'r');
        $file->open();
        $content = $file->read(1024);
        var_dump($content);
        self::assertIsString($content);
        $file->close();
    }

    public function testScanf()
    {
        $file = new File('../temp/test.txt', 'r');
        $file->open();
        $info = $file->scanf('%s');
        var_dump($info);
        self::assertIsArray($info);
        $file->close();
    }

    public function testStat()
    {
        $file = new File('../temp/test.txt', 'r');
        $file->open();
        $stat = $file->stat();
        var_dump($stat);
        self::assertIsArray($stat);
    }

    public function testTell()
    {
        $file = new File('../temp/test.txt', 'w');
        $file->open();
        $file->write("1234567890\r\n");
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
        $file = new File('../temp/test.txt', 'a+');
        $file->open();
        $rst = $file->truncate(5);
        self::assertTrue($rst);
        $file->close();
    }

    public function testWrite()
    {
        $file = new File('../temp/test.txt', 'w');
        $file->open();
        $len = $file->write("1234567890\r\n");
        $file->close();
        self::assertGreaterThan(0, $len);
    }

    public function testIsExecutable()
    {
        $file = new File('../temp/test.txt', 'w');
        $rst = $file->isExecutable();
        var_dump($rst);
        self::assertFalse($rst);
    }

    public function testIsFile()
    {
        $file = new File('../temp/test.txt', 'w');
        $rst = $file->isFile();
        self::assertTrue($rst);

        $file = new File('../temp/tesT.txt');
        $rst = $file->isFile();
        self::assertFalse($rst);
    }

    public function testIsLink()
    {
        $file = new File('../temp/test.txt', 'w');
        $rst = $file->isLink();
        var_dump($rst);
        self::assertFalse($rst);
    }

    public function testIsReadable()
    {
        $file = new File('../temp/test.txt', 'r');
        $rst = $file->isReadable();
        var_dump($rst);
        self::assertTrue($rst);
    }

    public function testIsUploadedFile()
    {
        $file = new File('../temp/test.txt');
        $bool = $file->isUploadedFile();
        var_dump($bool);
        self::assertFalse($bool);
    }

    public function testIsWriteable()
    {
        $file = new File('../temp/test.txt');
        $bool = $file->isWritable();
        var_dump($bool);
        self::assertTrue($bool);
    }

    public function testLink()
    {
        $file = new File('../temp/test.txt', 'r');
        $bool = $file->link('../temp/test_link2.txt');
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

    public function testPopen()
    {
        if (substr(php_uname(), 0, 7) == "Windows"){
            $cmd = "start /B php --version";
        } else {
            $cmd = "bash php --version";
        }
        $progress = new File();
        $progress->popen($cmd, 'r');
        $content = $progress->gets();
        var_dump($content);
        self::assertIsString($content);
        $progress->close();
    }

    public function testReadfile()
    {
        $file = new File('../temp/test.txt');
        $rst = $file->readfile();
        var_dump($rst);
        self::assertIsInt($rst);
    }

    public function testReadlink()
    {
        $file = new File('../temp/test_link.txt');
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

    public function testRealpath()
    {
        $file = new File('../temp/test.txt');
        $realpath = $file->realpath();
        var_dump($realpath);
        self::assertIsString($realpath);
    }

    public function testRename()
    {
        $file = new File('../temp/test2_new.txt');
        $rst = $file->rename('../temp/test2.txt');
        var_dump($rst);
        self::assertTrue($rst);
    }

    public function testRewind()
    {
        $file = new File('../temp/test.txt', 'r');
        $file->open();
        $content1 = $file->gets();
        var_dump($content1);
        $file->rewind();
        $content2 = $file->gets();
        var_dump($content2);
        self::assertEquals($content1, $content2);
        $file->close();
    }

    /**
     * @todo 测试未通过
     */
    public function testSetBuffer()
    {
        $fp = fopen('../temp/test.txt', "w");
        $rst = stream_set_write_buffer($fp, 0);
        var_dump($rst);
        self::assertEquals(-1, $rst);
        fclose($fp);

        $file = new File('../temp/test.txt', 'w');
        $file->write('123456');
        $file->write('654321');
        $file->rewind();
        $rst = $file->setBuffer(512);
        var_dump($rst);
        self::assertEquals(0, $rst);

        $len = $file->write("1234567890\r\n");
        $file->close();
        self::assertGreaterThan(0, $len);
    }

    /**
     * 需要以管理员权限运行
     */
    public function testSymlink()
    {
        $file = new File('../temp/test.txt');
        $bool = $file->symlink('../temp/test_symlink');
        var_dump($bool);
        self::assertTrue($bool);
    }

    public function testTmpfile()
    {
        define('PATH_ROOT', dirname(dirname(__FILE__)) . '/temp');
        Directory::chdir(PATH_ROOT);
        $resource = File::tmpfile();
        var_dump($resource);
        self::assertIsResource($resource);

        fwrite($resource, "这是一些测试字符串");
        fseek($resource, 0);
        $string = fread($resource, 1024);
        self::assertEquals("这是一些测试字符串", $string);
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

    public function testUnlink()
    {
        $file = new File('../temp/test3.txt');
        $result = $file->unlink();
        var_dump($result);
        self::assertTrue($result);
    }
}
