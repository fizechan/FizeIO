<?php

namespace Tests;

use Fize\IO\Directory;
use PHPUnit\Framework\TestCase;

class TestDirectory extends TestCase
{

    public function test__construct()
    {
        $root = dirname(__DIR__);

        $dir = new Directory( $root . "/temp");
        $dir->open();

        echo "---1---<br/>\r\n";
        $dir->read(function ($file) {
            echo "$file<br/>\r\n";
        });

        $dir->rewind();

        echo "---2---<br/>\r\n";
        $dir->read(function ($file) {
            echo "$file<br/>\r\n";
        });

        $dir->close();

        self::assertTrue(true);

        new Directory( $root . "/temp/temp/temp1", true);  // 已存在的文件夹
        self::assertTrue(Directory::exists($root . "/temp/temp/temp1"));

        new Directory( $root . "/temp/temp/temp2", true);  // 不存在的文件夹，自动建立
        self::assertTrue(Directory::exists($root . "/temp/temp/temp2"));

        new Directory( $root . "/temp/temp/temp3/temp33/temp333", true);  // 不存在的文件夹，自动建立(递归)
        self::assertTrue(Directory::exists($root . "/temp/temp/temp3/temp33/temp333"));

        new Directory( $root . "/temp/temp/temp4");  // 不存在的文件夹，不创建
        self::assertFalse(Directory::exists($root . "/temp/temp/temp4"));
    }

    public function test__destruct()
    {
        $root = dirname(__DIR__);
        $dir = new Directory($root . "/temp/中文目录123", true);
        $dir->open();

        echo "---1---<br/>\r\n";
        $dir->read(function ($file) {
            echo "$file<br/>\r\n";
        });

        $dir->rewind();

        echo "---2---<br/>\r\n";
        $dir->read(function ($file) {
            echo "$file<br/>\r\n";
        });

        $dir->close();

        self::assertTrue(true);
    }

    public function testOpen()
    {
        $root = dirname(__DIR__);
        $dir = new Directory($root . "/temp/temp1/temp11/temp111", true);
        $dir->open();

        echo "---1---<br/>\r\n";
        $dir->read(function ($file) {
            echo "$file<br/>\r\n";
        });

        $dir->rewind();

        echo "---2---<br/>\r\n";
        $dir->read(function ($file) {
            echo "$file<br/>\r\n";
        });

        $dir->close();

        self::assertTrue(true);
    }

    public function testClose()
    {
        $root = dirname(__DIR__);
        $dir = new Directory($root . "/temp/temp1");
        $dir->open();

        echo "---1---<br/>\r\n";
        $dir->read(function ($file) {
            echo "$file<br/>\r\n";
        });

        $dir->rewind();

        echo "---2---<br/>\r\n";
        $dir->read(function ($file) {
            echo "$file<br/>\r\n";
        });

        $dir->close();
        self::assertTrue(true);
    }

    public function testRead()
    {
        $root = dirname(__DIR__);
        $dir = new Directory($root . "/temp/temp1");
        $dir->open();

        echo "---1---<br/>\r\n";
        $dir->read(function ($file) {
            echo "$file<br/>\r\n";
        });

        $dir->rewind();

        echo "---2---<br/>\r\n";
        $dir->read(function ($file) {
            echo "$file<br/>\r\n";
        });

        $dir->close();

        self::assertTrue(true);
    }

    public function testRewind()
    {
        $root = dirname(__DIR__);
        $dir = new Directory($root . "/temp/temp1");
        $dir->open();

        echo "---1---<br/>\r\n";
        $dir->read(function ($file) {
            echo "$file<br/>\r\n";
        });

        $dir->rewind();

        echo "---2---<br/>\r\n";
        $dir->read(function ($file) {
            echo "$file<br/>\r\n";
        });

        $dir->close();
        self::assertTrue(true);
    }

    public function testClear()
    {
        $root = dirname(__DIR__);
        $dir = new Directory( $root . "/temp/temp1/测试目录1");
        $result = $dir->clear();
        var_dump($result);
        self::assertTrue($result);
    }

    public function testScan()
    {
        $root = dirname(__DIR__);
        $dir = new Directory( $root . "/temp/");
        $list = $dir->scan();
        var_dump($list);
        self::assertIsArray($list);
    }

    public function testTempnam()
    {
        $root = dirname(__DIR__);
        $dir = new Directory( $root . "/temp/temp/");
        $file_full_name = $dir->tempnam('txt');
        var_dump($file_full_name);
        self::assertIsString($file_full_name);
    }

    public function testCreate()
    {
        $root = dirname(__DIR__);
        $dir = new Directory( $root . "/temp/temp3");
        $result = $dir->create();
        self::assertTrue($result);
    }

    public function testDelete()
    {
        $root = dirname(__DIR__);

        $dir = new Directory( $root . "/temp/temp1");
        $dir->delete(true);

        $dir = new Directory( $root . "/temp/temp2");
        $dir->delete(true);

        $dir = new Directory( $root . "/temp/temp3");
        $result = $dir->delete();
        self::assertTrue($result);
    }

    public function testExists()
    {
        $root = dirname(__DIR__);

        self::assertTrue(Directory::exists($root . "/temp/data"));
        self::assertFalse(Directory::exists($root . "/temp/data2"));
    }

    public function testRealpath()
    {
        $root = dirname(__DIR__);

        $realpath1 = Directory::realpath($root . "/temp/data/");
        var_dump($realpath1);

        $realpath2 = Directory::realpath($root . "//temp//data");
        var_dump($realpath2);

        self::assertEquals($realpath1, $realpath2);

        $realpath3 = Directory::realpath($root . "//temp//data2", false);
        var_dump($realpath3);

        $realpath4 = Directory::realpath($root . "//temp//data2/test/../", false);
        var_dump($realpath4);

        self::assertEquals($realpath3, $realpath4);
    }
}
