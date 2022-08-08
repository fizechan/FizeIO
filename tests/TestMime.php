<?php

namespace Tests;

use Fize\IO\Mime;
use PHPUnit\Framework\TestCase;

class TestMime extends TestCase
{

    public function test__construct()
    {
        $mime = new Mime('video/3gp');
        var_dump($mime);
        self::assertInstanceOf(Mime::class, $mime);
    }

    public function testGetExtension()
    {
        $mime = new Mime('video/3gp');
        $ext = $mime->getExtension();
        self::assertEquals('3gp', $ext);
    }

    public function testGetByExtension()
    {
        $ext = '3gp';
        $mime = Mime::getByExtension($ext);
        self::assertEquals('video/3gp', $mime);
    }
}
