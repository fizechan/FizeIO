<?php


use fize\io\Directory;
use PHPUnit\Framework\TestCase;

class DirectoryTest extends TestCase
{

    public function test__construct()
    {
        $dir = new Directory("./data/dir0");
        $dir->open('.');

        echo "---1---<br/>\r\n";
        $dir->read(function ($file) {
            echo "{$file}<br/>\r\n";
        });

        $dir->rewind();

        echo "---2---<br/>\r\n";
        $dir->read(function ($file) {
            echo "{$file}<br/>\r\n";
        });

        $dir->close();

        self::assertTrue(true);
    }

    public function test__destruct()
    {
        $dir = new Directory("./data/dir0");
        $dir->open('.');

        echo "---1---<br/>\r\n";
        $dir->read(function ($file) {
            echo "{$file}<br/>\r\n";
        });

        $dir->rewind();

        echo "---2---<br/>\r\n";
        $dir->read(function ($file) {
            echo "{$file}<br/>\r\n";
        });

        $dir->close();

        self::assertTrue(true);
    }

    public function testDir()
    {
        Directory::ch("./data/dir0");

        $dir = Directory::dir('.');

        self::assertIsObject($dir);

        echo "---1---<br/>\r\n";

//$wd = Directory::getCwd();
//var_dump($wd);
//die();

        while ($file = $dir->read()) {
            echo "{$file}<br/>\r\n";
        }

        echo "---2---<br/>\r\n";
        while ($file = $dir->read()) {
            echo "{$file}<br/>\r\n";
        }

        echo "---3---<br/>\r\n";
        $dir->rewind();
        while ($file = $dir->read()) {
            echo "{$file}<br/>\r\n";
        }

        $dir->close();
    }

    public function testMk()
    {
        $root = dirname(__FILE__);
        var_dump($root);

        $wd = Directory::getcwd();
        var_dump($wd);

        $dir1 = new Directory("./data/dir1/dir2/测试目录3");
        var_dump($dir1);

        $wd = Directory::getcwd();
        var_dump($wd);

        $dir2 = new Directory("./data/dir4/dir5/测试目录6", true);
        var_dump($dir2);

        $wd = Directory::getcwd();
        var_dump($wd);

        $result = Directory::mk('./测试目录7/测试目录8');  //当前目录已在测试目录6
        self::assertTrue($result);

        $wd = Directory::getcwd();
        var_dump($wd);

        $result = Directory::mk($root . '/data/测试目录1/测试目录2');  //绝对路径
        self::assertTrue($result);

        $wd = Directory::getcwd();
        var_dump($wd);

        $wd = Directory::getcwd();
        var_dump($wd);
    }

    public function testOpen()
    {
        $dir = new Directory("./data/dir0");
        $dir->open('.');

        echo "---1---<br/>\r\n";
        $dir->read(function ($file) {
            echo "{$file}<br/>\r\n";
        });

        $dir->rewind();

        echo "---2---<br/>\r\n";
        $dir->read(function ($file) {
            echo "{$file}<br/>\r\n";
        });

        $dir->close();

        self::assertTrue(true);
    }

    public function testClose()
    {
        $dir = new Directory("./data/dir0");
        $dir->open('.');

        echo "---1---<br/>\r\n";
        $dir->read(function ($file) {
            echo "{$file}<br/>\r\n";
        });

        $dir->rewind();

        echo "---2---<br/>\r\n";
        $dir->read(function ($file) {
            echo "{$file}<br/>\r\n";
        });

        $dir->close();
        self::assertTrue(true);
    }

    public function testCh()
    {
        define('PATH_ROOT', dirname(__FILE__));

        $dir = new Directory("./data");
        $dir->close();
        $path1 = Directory::getcwd();

        $result = Directory::ch(PATH_ROOT . '/data/dir0/测试目录1');
        self::assertTrue($result);

        $path2 = Directory::getcwd();
        self::assertNotEquals($path1, $path2);
    }

    public function testChroot()
    {
        define('PATH_ROOT', dirname(__FILE__));

        $result = Directory::chroot(PATH_ROOT . '/data/dir0/测试目录1');
        var_dump($result);
        self::assertTrue($result);
    }

    public function testGetcwd()
    {
        $root = dirname(__FILE__);
        var_dump($root);

        $wd0 = Directory::getcwd();
        var_dump($wd0);

        $dir1 = new Directory("./data/dir1/dir2/测试目录3");  //该文件夹不存在，当前工作目录并不转移
        var_dump($dir1);

        $wd1 = Directory::getcwd();
        var_dump($wd1);

        self::assertEquals($wd0, $wd1);

        $dir2 = new Directory("./data/dir4/dir5/测试目录6", true);  //当前工作目录转移到"测试目录6"
        var_dump($dir2);

        $wd2 = Directory::getcwd();
        var_dump($wd2);

        self::assertNotEquals($wd1, $wd2);

        Directory::mk('./测试目录7/测试目录8');  //创建文件夹并不会转移当前工作目录

        $wd3 = Directory::getcwd();
        var_dump($wd3);
        self::assertEquals($wd2, $wd3);

        Directory::mk($root . '/data/测试目录1/测试目录2');  //绝对路径

        $wd4 = Directory::getcwd();
        var_dump($wd4);
        self::assertEquals($wd3, $wd4);

        $wd5 = Directory::getcwd();
        var_dump($wd5);
        self::assertEquals($wd4, $wd5);
    }

    public function testRead()
    {
        $dir = new Directory("./data/dir0");
        $dir->open('.');

        echo "---1---<br/>\r\n";
        $dir->read(function ($file) {
            echo "{$file}<br/>\r\n";
        });

        $dir->rewind();

        echo "---2---<br/>\r\n";
        $dir->read(function ($file) {
            echo "{$file}<br/>\r\n";
        });

        $dir->close();

        self::assertTrue(true);
    }

    public function testRewind()
    {
        $dir = new Directory("./data/dir0");
        $dir->open('.');

        echo "---1---<br/>\r\n";
        $dir->read(function ($file) {
            echo "{$file}<br/>\r\n";
        });

        $dir->rewind();

        echo "---2---<br/>\r\n";
        $dir->read(function ($file) {
            echo "{$file}<br/>\r\n";
        });

        $dir->close();
        self::assertTrue(true);
    }

    public function testScan()
    {
        $result = Directory::ch('./data/dir0/测试目录1');
        var_dump($result);
        $list = Directory::scan('.');
        var_dump($list);
        self::assertIsArray($list);
    }

    public function testCreateFile()
    {
        define('PATH_ROOT', dirname(__FILE__));

        $result = Directory::ch(PATH_ROOT . '/data/dir0/测试目录1');
        var_dump($result);

        $result = Directory::createFile('test22');
        var_dump($result);
        self::assertTrue($result);

        $result = Directory::createFile('test22.txt');
        var_dump($result);
        self::assertTrue($result);

        $result = Directory::createFile('测试文件22.txt');
        var_dump($result);
        self::assertTrue($result);

        $result = Directory::createFile('./新建文件夹/测试文件22.txt', true);
        var_dump($result);
        self::assertTrue($result);
    }

    public function testCreateDirectory()
    {
        define('PATH_ROOT', dirname(__FILE__));

        $result = Directory::ch(PATH_ROOT . '/data/dir0/测试目录1');
        var_dump($result);

        $result = Directory::createDirectory('新建文件夹2');
        self::assertTrue($result);

        $result = Directory::createDirectory('./新建文件夹3/新建文件夹33/新建文件夹333', 0777, true);
        self::assertTrue($result);
    }

    public function testDeleteFile()
    {
        define('PATH_ROOT', dirname(__FILE__));

        $result = Directory::ch(PATH_ROOT . '/data/dir0/测试目录1');
        var_dump($result);

        $result = Directory::deleteFile('test22');
        self::assertTrue($result);

        $result = Directory::deleteFile('./新建文件夹/测试文件22.txt');
        self::assertTrue($result);
    }

    public function testDeleteDirectory()
    {
        define('PATH_ROOT', dirname(__FILE__));

        $result = Directory::ch(PATH_ROOT . '/data/dir0');
        self::assertTrue($result);

        $result = Directory::deleteDirectory('test1');
        self::assertTrue($result);

        $result = Directory::deleteDirectory('test3', true);
        self::assertTrue($result);

        $result = Directory::deleteDirectory('./测试目录2/测试目录11', true);
        self::assertTrue($result);
    }

    public function testClear()
    {
        define('PATH_ROOT', dirname(__FILE__));

        $result = Directory::ch(PATH_ROOT . '/data/dir0/测试目录2');
        var_dump($result);

        $result = Directory::clear();
        var_dump($result);
        self::assertTrue($result);
    }

    public function testIsDir()
    {
        Directory::ch("./data/dir0");

        $result = Directory::isDir('testdir1');
        self::assertTrue($result);

        $result = Directory::isDir('test2');
        self::assertFalse($result);

        $result = Directory::isDir('./测试目录1/测试目录11');
        self::assertTrue($result);
    }

    public function testCreateTempFile()
    {
        Directory::ch("./data/dir0");

        $file_full_name = Directory::createTempFile('test');
        var_dump($file_full_name);
        self::assertIsString($file_full_name);
    }

    public function testGlob()
    {
        Directory::ch("./data/dir0");

        $result = Directory::glob('*.xlsx');
        var_dump($result);
        self::assertIsArray($result);
    }

    public function testDiskFreeSpace()
    {
        $space = Directory::diskFreeSpace("./data/dir0");
        var_dump($space);
        self::assertIsFloat($space);
    }

    public function testDiskTotalSpace()
    {
        $space = Directory::diskTotalSpace("./data/dir0");
        var_dump($space);
        self::assertIsFloat($space);
    }
}
