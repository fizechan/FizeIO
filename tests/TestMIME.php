<?php

namespace Tests;

use Fize\IO\MIME;
use PHPUnit\Framework\TestCase;

class TestMIME extends TestCase
{

    public function test__construct()
    {
        $mime = new MIME('video/3gp');
        var_dump($mime);
        self::assertNotNull($mime);
    }

    public function testGetExtension()
    {
        $mime = new MIME('video/3gp');
        $ext = $mime->getExtension();
        self::assertEquals('3gp', $ext);
    }

    public function testGetByExtension()
    {
        $ext = '3gp';
        $mime = MIME::getByExtension($ext);
        self::assertEquals('video/3gp', $mime);
    }
}
