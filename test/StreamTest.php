<?php


use fize\io\Stream;
use PHPUnit\Framework\TestCase;

class StreamTest extends TestCase
{

    public function testBucketAppend()
    {
        self::assertTrue(true);
    }

    public function testContextCreate()
    {
        $resource = Stream::contextCreate();
        var_dump($resource);
        self::assertIsResource($resource);
    }
}
