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

    public function testGetStream()
    {
        if (substr(php_uname(), 0, 7) == 'Windows') {
            $cmd = 'start /B php --version';
        } else {
            $cmd = 'bash php --version';
        }
        $progress = new FileP();
        $progress->open($cmd, 'r');
        $stream = $progress->getStream();
        var_dump($stream);
        self::assertIsResource($stream);
    }

    public function testEof()
    {
        if (substr(php_uname(), 0, 7) == 'Windows') {
            $cmd = 'echo "test"';
        } else {
            $cmd = 'bash echo "test"';
        }
        $progress = new FileP();
        $progress->open($cmd, 'r');
        $rst = $progress->eof();
        self::assertFalse($rst);
        $content = $progress->read(100);
        var_dump($content);
        $rst = $progress->eof();
        var_dump($rst);
        self::assertIsBool($rst);
    }

    public function testFlush()
    {
        if (substr(php_uname(), 0, 7) == 'Windows') {
            $cmd = 'start /B php --version > ../temp/testFilePFlush.txt';
        } else {
            $cmd = 'bash php --version > ../temp/testFilePFlush.txt';
        }
        $progress = new FileP();
        $progress->open($cmd, 'w');
        $rst = $progress->flush();
        self::assertTrue($rst);
    }

    public function testGetc()
    {
        if (substr(php_uname(), 0, 7) == 'Windows') {
            $cmd = 'start /B echo "ABC"';
        } else {
            $cmd = 'bash echo "ABC"';
        }
        $progress = new FileP();
        $progress->open($cmd, 'r');
        $c = $progress->getc();
        var_dump($c);
        self::assertIsString($c);
    }

    public function testGetss()
    {
        if (substr(php_uname(), 0, 7) == 'Windows') {
            $cmd = 'start /B echo "<p>test</p>"';
        } else {
            $cmd = 'bash echo "<p>test</p>"';
        }
        $progress = new FileP();
        $progress->open($cmd, 'r');
        $content = $progress->getss();
        var_dump($content);
        self::assertIsString($content);
    }

    public function testLock()
    {
        self::markTestSkipped('FileP 使用 popen() 不支持文件锁操作');
    }

    public function testPuts()
    {
        if (substr(php_uname(), 0, 7) == 'Windows') {
            $cmd = 'start /B php --version > ../temp/testFilePPuts.txt';
        } else {
            $cmd = 'bash php --version > ../temp/testFilePPuts.txt';
        }
        $progress = new FileP();
        $progress->open($cmd, 'w');
        $len = $progress->puts('test');
        var_dump($len);
        self::assertIsInt($len);
    }

    public function testScanf()
    {
        if (substr(php_uname(), 0, 7) == 'Windows') {
            $cmd = 'start /B echo "test 123"';
        } else {
            $cmd = 'bash echo "test 123"';
        }
        $progress = new FileP();
        $progress->open($cmd, 'r');
        $info = $progress->scanf('%s');
        var_dump($info);
        self::assertIsArray($info);
    }

    public function testSeek()
    {
        self::markTestSkipped('FileP 使用 popen() 不支持 seek 操作');
    }

    public function testTell()
    {
        if (substr(php_uname(), 0, 7) == 'Windows') {
            $cmd = 'start /B echo "1234567890"';
        } else {
            $cmd = 'bash echo "1234567890"';
        }
        $progress = new FileP();
        $progress->open($cmd, 'r');
        $cursor1 = $progress->tell();
        var_dump($cursor1);
        self::assertEquals(0, $cursor1);
        $content = $progress->gets(5);
        var_dump($content);
        $cursor2 = $progress->tell();
        var_dump($cursor2);
        self::assertNotEquals($cursor1, $cursor2);
    }

    public function testTruncate()
    {
        self::markTestSkipped('FileP 使用 popen() 不支持 truncate 操作');
    }

    public function testRewind()
    {
        self::markTestSkipped('FileP 使用 popen() 不支持 rewind 操作');
    }

    public function testSetBuffer()
    {
        if (substr(php_uname(), 0, 7) == 'Windows') {
            $cmd = 'echo "test" > ../temp/testFilePSetBuffer.txt';
        } else {
            $cmd = 'bash echo "test" > ../temp/testFilePSetBuffer.txt';
        }
        $file = new FileP();
        $file->open($cmd, 'w');
        $rst = $file->setBuffer(512);
        var_dump($rst);
        self::assertIsInt($rst);
    }
}
