<?php
require_once "../vendor/autoload.php";

use fize\io\Stream;
use fize\io\File;

$src = new File('https://www.baidu.com', 'r');
$src->open();
$dest = new File('../temp/baidu.txt', 'w');
$dest->open();
$stream = new Stream($src->getStream());
$rst = $stream->copyToStream($dest->getStream());
var_dump($rst);