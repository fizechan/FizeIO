<?php
require_once "../vendor/autoload.php";

use fize\io\Stream;
use fize\io\File;

$stream = new Stream('https://www.baidu.com', 'r');
$fso = fopen('../temp/baidu.txt', 'w');
$rst = $stream->copyToStream($fso);
var_dump($rst);

$dest = new File($fso);
var_dump($dest->getContents());
