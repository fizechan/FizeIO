<?php
require_once "../vendor/autoload.php";

use fize\io\Stream;
use fize\io\File;


/**
 * 自定义过滤器
 *
 * 该过滤器功能为使所有字符串改为大写
 */
class StrToUpperFilter extends php_user_filter
{
    function filter($in, $out, &$consumed, $closing)
    {
        while ($bucket = stream_bucket_make_writeable($in)) {
            $bucket->data = strtoupper($bucket->data);
            $consumed += $bucket->datalen;
            stream_bucket_append($out, $bucket);
        }
        return PSFS_PASS_ON;
    }
}



$rst = Stream::filterRegister("strtoupper", "StrToUpperFilter");
var_dump($rst);

$fp = new File('../temp/testStreamFilterRegister.txt', 'w+');
$fp->open();
$stream = new Stream($fp->getStream());
$stream->filterAppend("strtoupper");
$fp->write("Line1\n");
$fp->write("Word - 2\n");
$fp->write("Easy As 123\n");
$fp->close();
$fp->readfile();