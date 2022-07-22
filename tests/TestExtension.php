<?php

namespace Tests;

use Fize\IO\Extension;
use PHPUnit\Framework\TestCase;

class TestExtension extends TestCase
{

    public function testIsImage()
    {
        $is_image1 = Extension::isImage('png');
        self::assertTrue($is_image1);
        $is_image2 = Extension::isImage('docx');
        self::assertFalse($is_image2);
    }
}
