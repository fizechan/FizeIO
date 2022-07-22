<?php

namespace Tests;

use Exception;
use Fize\IO\StreamWrapper;
use PHPUnit\Framework\TestCase;

class TestStreamWrapper extends TestCase
{

    public function testGets()
    {
        $wrappers = StreamWrapper::gets();
        var_dump($wrappers);
        self::assertIsArray($wrappers);
    }

    public function testRegister()
    {
        $rst = StreamWrapper::register('txt', TxtStreamWrapper::class);
        var_dump($rst);
        self::assertTrue($rst);
    }

    public function testRestore()
    {
        $existed = in_array('http', StreamWrapper::gets());
        if ($existed) {
            StreamWrapper::unregister('http');
        }
        $rst = StreamWrapper::register('http', TxtStreamWrapper::class);
        self::assertTrue($rst);
        $myvar = '';

        $fp = fopen('http://myvar', 'r+');

        fwrite($fp, "line1\n");
        fwrite($fp, "line2\n");
        fwrite($fp, "line3\n");

        rewind($fp);
        while (!feof($fp)) {
            echo fgets($fp);
        }
        fclose($fp);
        var_dump($myvar);

        $existed = in_array('http', StreamWrapper::gets());
        if ($existed) {
            $rst = StreamWrapper::restore('http');
            var_dump($rst);
            self::assertTrue($rst);
        }
        $rst = StreamWrapper::restore('http');
        var_dump($rst);
        self::assertTrue($rst);
    }

    public function testUnregister()
    {
        StreamWrapper::register('var', TxtStreamWrapper::class);
        $rst = StreamWrapper::unregister('var');
        var_dump($rst);
        self::assertTrue($rst);
    }

}

/**
 * 自定义封装协议
 */
class TxtStreamWrapper
{
    public const WRAPPER_NAME = 'callback';

    public $context;

    private $seek = 0;

    private $eof = false;

    private static $isRegistered = false;

    /**
     * 获取上下文
     * @param $cb
     * @return resource
     */
    public static function getContext($cb)
    {
        if (!self::$isRegistered) {
            stream_wrapper_register(self::WRAPPER_NAME, get_class());
            self::$isRegistered = true;
        }
        if (!is_callable($cb)) {
            throw new Exception('error on getContext');
        }
        return stream_context_create([self::WRAPPER_NAME => ['cb' => $cb]]);
    }

    public function stream_open($path, $mode, $options, &$opened_path): bool
    {
        var_dump($path);
        var_dump($mode);
        var_dump($options);
        $opened_path = '';
        var_dump($opened_path);
        return true;
    }

    public function stream_read($count): string
    {
        $this->seek = $this->seek + $count;
        if ($this->seek > 10) {
            $this->eof = true;
        }
        return (string)$this->seek;
    }

    public function stream_write($data): int
    {
        return strlen($data);
    }

    public function stream_seek($offset, $whence = 0): bool
    {
        var_dump($offset);
        var_dump($whence);
        return true;
    }

    public function stream_tell(): int
    {
        return 1;
    }

    public function stream_eof(): bool
    {
        return $this->eof;
    }
}
