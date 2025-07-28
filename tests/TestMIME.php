<?php

namespace Tests;

use Fize\IO\MIME;
use PHPUnit\Framework\TestCase;

class TestMIME extends TestCase
{

    public function testGetExtensionByMime()
    {
        $ext1 = MIME::getExtensionByMime('application/json');
        self::assertEquals('json', $ext1);
        $ext2 = MIME::getExtensionByMime('application/octet-stream');
        self::assertEquals('bin,dms,lrf,mar,so,dist,distz,pkg,bpk,dump,elc,deploy', $ext2);
        $ext3 = MIME::getExtensionByMime('image/jpeg', true);
        self::assertEquals('jpg', $ext3);
    }

    public function testGetMimeByExtension()
    {
        $mime1 = MIME::getMimeByExtension('js');
        self::assertEquals('application/javascript', $mime1);
        $mime2 = MIME::getMimeByExtension('jpeg');
        self::assertEquals('image/jpeg', $mime2);
    }

    public function testHasMultipleExtensions()
    {
        $has1 = MIME::hasMultipleExtensions('application/json');
        self::assertFalse($has1);
        $has2 = MIME::hasMultipleExtensions('application/octet-stream');
        self::assertTrue($has2);
    }
}
