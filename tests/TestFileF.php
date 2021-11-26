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
}
