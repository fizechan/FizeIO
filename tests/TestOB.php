<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Fize\IO\OB;
use GuzzleHttp\Client;

class TestOB extends TestCase
{

    public function testClean()
    {
        OB::start();
        echo '1';
        echo '2';
        OB::clean();
        echo '3';
        echo '4';
        $ob = OB::getClean();
        self::assertEquals('34', $ob);
    }

    public function testEndClean()
    {
        OB::start();
        echo '1';
        echo '2';
        OB::endClean();
        OB::start();
        echo '3';
        echo '4';
        $ob = OB::getClean();
        self::assertEquals('34', $ob);
    }

    public function testEndFlush()
    {
        OB::start();
        echo '1';
        echo '2';
        OB::endFlush();
        OB::start();  //要重新打开了
        echo '3';
        echo '4';
        $ob = OB::getClean();
        self::assertEquals('34', $ob);
    }

    public function testFlush()
    {
        OB::start();
        echo '1';
        echo '2';
        OB::flush();
        echo '3';
        echo '4';
        $ob = OB::getClean();
        self::assertEquals('34', $ob);
    }

    public function testGetClean()
    {
        OB::start();
        echo '1';
        echo '2';
        OB::endClean();
        OB::start();
        echo '3';
        echo '4';
        $ob = OB::getClean();
        self::assertEquals('34', $ob);
    }

    public function testGetContents()
    {
        OB::start();
        echo '1';
        echo '2';
        echo '3';
        echo '4';
        $ob = OB::getContents();
        OB::endClean();
        self::assertEquals('1234', $ob);
    }

    public function testGetFlush()
    {
        OB::start();
        echo '1';
        echo '2';
        echo '3';
        echo '4';
        $ob = OB::getFlush();  //调用后缓冲区自动关闭了
        self::assertEquals('1234', $ob);
    }

    public function testGetLength()
    {
        OB::start();
        echo '1';
        echo '2';
        echo '3';
        echo '4';
        $ob_length = OB::getLength();
        OB::endClean();
        self::assertEquals(4, $ob_length);
    }

    public function testGetLevel()
    {
        OB::start();
        echo '1';
        echo '2';
        echo '3';
        echo '4';
        $ob_level = OB::getLevel();
        OB::endClean();
        self::assertEquals(2, $ob_level);
    }

    public function testGetStatus()
    {
        OB::start();
        echo '1';
        echo '2';
        echo '3';
        echo '4';
        OB::endClean();
        echo '5';
        $statuses = OB::getStatus(true);
        var_dump($statuses);

        self::assertIsArray($statuses);
    }

    /**
     * @todo 该测试实际未进行
     */
    public function testGzhandler()
    {
        $str = OB::gzhandler('测试看看', 1);
        var_dump($str);
        self::assertIsString($str);

        $cmd = 'start cmd /k "cd /d %cd%/../examples &&php -S localhost:8123"';
        $pid = popen($cmd, 'r');
        pclose($pid);
        sleep(3);  //待服务器启动

        $client = new Client([
            'base_uri' => 'http://localhost:8123'
        ]);

        $response = $client->get('gzhandler.php');

        $body = $response->getBody();
        self::assertEquals('1234', (string)$body);
    }

    public function testImplicitFlush()
    {
        OB::start();
        OB::implicitFlush(true);
        echo '1';
        echo '2';
        echo '3';
        echo '4';
        OB::endClean();
        self::assertTrue(true);
    }

    public function testListHandlers()
    {
        $handlers = OB::listHandlers();
        var_dump($handlers);
        self::assertIsArray($handlers);
    }

    public function testStart()
    {
        $result = OB::start();
        OB::implicitFlush(true);
        echo '1';
        echo '2';
        echo '3';
        echo '4';
        OB::endClean();
        self::assertTrue($result);
    }
}
