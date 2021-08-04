<?php

use fize\io\FileP;
use PHPUnit\Framework\TestCase;

class TestFileP extends TestCase
{

    public function test__construct()
    {
        if (substr(php_uname(), 0, 7) == "Windows"){
            $cmd = "start /B php --version";
        } else {
            $cmd = "bash php --version";
        }
        $progress = new FileP($cmd, 'r');
        $content = $progress->gets();
        var_dump($content);
        self::assertIsString($content);
    }

    public function test__destruct()
    {
        if (substr(php_uname(), 0, 7) == "Windows"){
            $cmd = "start /B php --version";
        } else {
            $cmd = "bash php --version";
        }
        $progress = new FileP($cmd, 'r');
        $line_content = $progress->gets();
        var_dump($line_content);
        self::assertIsString($line_content);
        unset($progress);
    }

    public function testGets()
    {
        if (substr(php_uname(), 0, 7) == "Windows"){
            $cmd = "start /B php --version";
        } else {
            $cmd = "bash php --version";
        }
        $progress = new FileP($cmd, 'r');
        $content = $progress->gets();
        var_dump($content);
        self::assertIsString($content);
    }

    public function testPassthru()
    {
        if (substr(php_uname(), 0, 7) == "Windows"){
            $cmd = "start /B php --version";
        } else {
            $cmd = "bash php --version";  //@todo 待验证
        }
        $file = new FileP($cmd, 'r');
        $len = $file->passthru();
        self::assertIsInt($len);
    }

    public function testRead()
    {
        if (substr(php_uname(), 0, 7) == "Windows"){
            $cmd = "start /B php --version";
        } else {
            $cmd = "bash php --version";
        }
        $progress = new FileP($cmd, 'r');
        $content = $progress->read(1024);
        var_dump($content);
        self::assertIsString($content);
    }

    public function testWrite()
    {
        if (substr(php_uname(), 0, 7) == "Windows"){
            $cmd = "start /B php --version > ../temp/cfztest.txt";
        } else {
            $cmd = "bash php --version > ../temp/cfztest.txt";
        }
        $progress = new FileP($cmd, 'w');
        $len = $progress->write($cmd);
        self::assertGreaterThan(0, $len);
    }

    public function testOpen()
    {
        if (substr(php_uname(), 0, 7) == "Windows"){
            $cmd = "start /B php --version > ../temp/cfztest.txt";
        } else {
            $cmd = "bash php --version > ../temp/cfztest.txt";
        }
        $fp = new FileP($cmd, 'w');
        $fp->close();
        $fp->open($cmd, 'w');
    }
}
