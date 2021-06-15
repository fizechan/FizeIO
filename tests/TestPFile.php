<?php


use fize\io\PFile;
use PHPUnit\Framework\TestCase;

class TestPFile extends TestCase
{

    public function test__construct()
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

    public function testClose()
    {

    }

    public function testGetss()
    {

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

    public function test__destruct()
    {

    }

    public function testGets()
    {

    }

    public function testWrite()
    {
        $file = new File('../temp/test.txt', 'w');
        $file->open();
        $len = $file->write("1234567890\r\n");
        $file->close();
        self::assertGreaterThan(0, $len);
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

    public function testEof()
    {

    }
}
