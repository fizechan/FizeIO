<?php

namespace Tests;

use Fize\IO\Disk;
use PHPUnit\Framework\TestCase;

class TestDisk extends TestCase
{

    public function testFreeSpace()
    {
        $root = dirname(__DIR__);
        $disk = new Disk($root . '/temp');
        $space = $disk->freeSpace();
        var_dump($space);
        self::assertIsFloat($space);
    }

    public function testTotalSpace()
    {
        $root = dirname(__DIR__);
        $disk = new Disk($root . '/temp');
        $space = $disk->totalSpace();
        var_dump($space);
        self::assertIsFloat($space);
    }

    public function test__construct()
    {
        $root = dirname(__DIR__);
        $disk = new Disk($root . '/temp');
        self::assertNotNull($disk);
    }

    public function testOpen()
    {
        $root = dirname(__DIR__);
        $disk = new Disk($root . '/temp');
        $disk->open();
        self::assertTrue(true);
        $disk->close();
    }

    public function testClose()
    {
        $root = dirname(__DIR__);
        $disk = new Disk($root . '/temp');
        $disk->open();
        $disk->close();
        self::assertTrue(true);
    }

    public function testRead()
    {
        $root = dirname(__DIR__);
        $disk = new Disk($root . '/temp');
        $disk->open();
        $disk->read(function ($file) {
            echo "$file<br/>\r\n";
        });
        $disk->close();
        self::assertTrue(true);
    }

    public function testRewind()
    {
        $root = dirname(__DIR__);
        $disk = new Disk($root . '/temp');
        $disk->open();
        $disk->read(function ($file) {
            echo "$file<br/>\r\n";
        });
        $disk->rewind();
        $disk->read(function ($file) {
            echo "$file<br/>\r\n";
        });
        $disk->close();
        self::assertTrue(true);
    }

    public function testClear()
    {
        $root = dirname(__DIR__);
        $disk = new Disk($root . '/temp/testDiskClear');
        if (!Disk::exists($root . '/temp/testDiskClear')) {
            $disk->create(true);
        }
        $disk->open();
        $disk->close();
        $result = $disk->clear();
        var_dump($result);
        self::assertTrue($result);
    }

    public function testScan()
    {
        $root = dirname(__DIR__);
        $disk = new Disk($root . '/temp');
        $list = $disk->scan();
        var_dump($list);
        self::assertIsArray($list);
    }

    public function testTempnam()
    {
        $root = dirname(__DIR__);
        $disk = new Disk($root . '/temp');
        $file_full_name = $disk->tempnam('tmp');
        var_dump($file_full_name);
        self::assertTrue(is_string($file_full_name) || $file_full_name === false);
    }

    public function testCreate()
    {
        $root = dirname(__DIR__);
        $disk = new Disk($root . '/temp/testDiskCreate');
        $result = $disk->create();
        self::assertTrue($result);
    }

    public function testDelete()
    {
        $root = dirname(__DIR__);
        $disk = new Disk($root . '/temp/testDiskCreate');
        $result = $disk->delete();
        self::assertTrue($result);
    }

    public function testExists()
    {
        $root = dirname(__DIR__);
        self::assertTrue(Disk::exists($root . '/temp'));
        self::assertFalse(Disk::exists($root . '/temp_not_exists'));
    }

    public function testRealpath()
    {
        $root = dirname(__DIR__);
        $realpath1 = Disk::realpath($root . '/temp/');
        var_dump($realpath1);
        self::assertIsString($realpath1);
    }
}
