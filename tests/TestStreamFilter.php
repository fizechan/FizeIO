<?php


use fize\io\StreamFilter;
use PHPUnit\Framework\TestCase;

class TestStreamFilter extends TestCase
{

    public function testAppend()
    {
        $res = StreamFilter::append("string.rot13", STREAM_FILTER_WRITE);
        self::assertIsResource($res);
        $fp = new File($stream->get());
        $fp->write("This is a test\n");
        $fp->rewind();
        $fp->passthru();
        $fp->close();
    }

    public function testFilterPrepend()
    {

        $stream = new Stream('../temp/testStreamFilterAppend.txt', 'w+');
        $res = $stream->filterPrepend("string.rot13", STREAM_FILTER_WRITE);
        self::assertIsResource($res);
        $fp = new File($stream->get());
        $fp->write("This is a test\n");
        $fp->rewind();
        $fp->passthru();
        $fp->close();
    }

    public function testFilterRegister()
    {
        $rst = Stream::filterRegister("strtoupper", "strtoupper_filter");
        var_dump($rst);
        self::assertTrue($rst);

        $stream = new Stream('../temp/testStreamFilterRegister.txt', 'w+');
        $stream->filterAppend("strtoupper");
        $fp = new File($stream->get());
        $fp->write("Line1\n");
        $fp->write("Word - 2\n");
        $fp->write("Easy As 123\n");
        $fp->rewind();
        $content = $fp->read(1000);
        $fp->close();
        var_dump($content);
    }

    public function testFilterRemove()
    {
        $stream = new Stream('../temp/testStreamFilterRemove.txt', 'w+');
        $filter = $stream->filterAppend("string.rot13", STREAM_FILTER_WRITE);
        $rst = Stream::filterRemove($filter);
        self::assertTrue($rst);
        $fp = new File($stream->get());
        $fp->write("This is a test\n");
        $fp->rewind();
        $fp->passthru();
        $fp->close();
    }

}
