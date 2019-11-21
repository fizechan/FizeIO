<?php


use fize\io\Stream;
use PHPUnit\Framework\TestCase;

class StreamTest extends TestCase
{

    /**
     * @todo 因为PHP文档问题，暂时无法测试
     */
    public function testBucketAppend()
    {
        self::assertTrue(true);
    }

    /**
     * @todo 因为PHP文档问题，暂时无法测试
     */
    public function testBucketMakeWriteable()
    {
        self::assertTrue(true);
    }

    /**
     * @todo 因为PHP文档问题，暂时无法测试
     */
    public function testBucketNew()
    {
        self::assertTrue(true);
    }

    /**
     * @todo 官方文档过于复杂，暂时不测试
     */
    public function testBucketPrepend()
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
