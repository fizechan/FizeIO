<?php
require_once "../../vendor/autoload.php";

use fize\io\FileF;
use fize\io\Stream;
use fize\io\StreamFilter;

/**
 * 自定义过滤器
 *
 * 该过滤器功能为使所有字符串改为大写
 */
class StrToUpperFilter extends php_user_filter
{
    function filter($in, $out, &$consumed, $closing): int
    {
        while ($bucket = stream_bucket_make_writeable($in)) {
            $bucket->data = strtoupper($bucket->data);
            $consumed += $bucket->datalen;
            stream_bucket_append($out, $bucket);
        }
        return PSFS_PASS_ON;
    }
}



$rst = StreamFilter::register("strtoupper", StrToUpperFilter::class);
var_dump($rst);

StreamFilter::append("strtoupper");

$stream = new Stream();
$stream->open('../temp/testStreamFilterRegister.txt', 'w+');
$stream->filterAppend("strtoupper");
$fp = new FileF($stream->get());
$fp->write("Line1\n");
$fp->write("Word - 2\n");
$fp->write("Easy As 123\n");
$fp->close();
$fp->readfile();
