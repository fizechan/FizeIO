<?php

namespace Tests;

use Fize\IO\FileP;
use PHPUnit\Framework\TestCase;

class TestFileP extends TestCase
{

    public function test__construct()
    {
        if (substr(php_uname(), 0, 7) == 'Windows') {
            $cmd = 'start /B php --version';
        } else {
            $cmd = 'bash php --version';
        }
        $progress = new FileP();
        $progress->open($cmd, 'r');
        $content = $progress->gets();
        var_dump($content);
        self::assertIsString($content);
    }

    public function test__destruct()
    {
        if (substr(php_uname(), 0, 7) == 'Windows') {
            $cmd = 'start /B php --version';
        } else {
            $cmd = 'bash php --version';
        }
        $progress = new FileP();
        $progress->open($cmd, 'r');
        $line_content = $progress->gets();
        var_dump($line_content);
        self::assertIsString($line_content);
        unset($progress);
    }

    public function testClose()
    {
        if (substr(php_uname(), 0, 7) == 'Windows') {
            $cmd = 'start /B php --version > ../temp/cfztest.txt';
        } else {
            $cmd = 'bash php --version > ../temp/cfztest.txt';
        }
        $fp = new FileP();
        $fp->open($cmd, 'w');
        $rst = $fp->close();
        self::assertNotEquals(-1, $rst);
    }

    public function testOpen()
    {
        if (substr(php_uname(), 0, 7) == 'Windows') {
            $cmd = 'start /B php --version > ../temp/cfztest.txt';
        } else {
            $cmd = 'bash php --version > ../temp/cfztest.txt';
        }
        $fp = new FileP();
        $fp->open($cmd, 'w');
        $fp->close();
        $fp->open($cmd, 'w');
        self::assertTrue(true);
    }

    public function testGets()
    {
        if (substr(php_uname(), 0, 7) == 'Windows') {
            $cmd = 'start /B php --version';
        } else {
            $cmd = 'bash php --version';
        }
        $progress = new FileP();
        $progress->open($cmd, 'r');
        $content = $progress->gets();
        var_dump($content);
        self::assertIsString($content);
    }

    public function testPassthru()
    {
        if (substr(php_uname(), 0, 7) == 'Windows') {
            $cmd = 'start /B php --version';
        } else {
            $cmd = 'bash php --version';  //@todo 待验证
        }
        $file = new FileP();
        $file->open($cmd, 'r');
        $len = $file->passthru();
        self::assertIsInt($len);
    }

    public function testRead()
    {
        if (substr(php_uname(), 0, 7) == 'Windows') {
            $cmd = 'start /B php --version';
        } else {
            $cmd = 'bash php --version';
        }
        $progress = new FileP();
        $progress->open($cmd, 'r');
        $content = $progress->read(1024);
        var_dump($content);
        self::assertIsString($content);
    }

    public function testWrite()
    {
        if (substr(php_uname(), 0, 7) == 'Windows') {
            $cmd = 'start /B php --version > ../temp/cfztest.txt';
        } else {
            $cmd = 'bash php --version > ../temp/cfztest.txt';
        }
        $progress = new FileP();
        $progress->open($cmd, 'w');
        $len = $progress->write($cmd);
        self::assertGreaterThan(0, $len);
    }
}
