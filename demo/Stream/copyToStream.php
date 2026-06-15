<?php
require_once "../../vendor/autoload.php";

use Fize\IO\Stream;

$stream = new Stream();
$stream->open('https://www.baidu.com', 'r');
$fso = fopen('../temp/baidu.txt', 'w');
$rst = $stream->copyToStream($fso);
var_dump($rst);

$dest = new Stream($fso);
var_dump($dest->getContents());
