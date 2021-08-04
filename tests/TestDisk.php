<?php

use fize\io\Disk;
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
}
