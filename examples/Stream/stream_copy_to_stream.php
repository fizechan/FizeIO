<?php
require_once "../../vendor/autoload.php";

use fize\io\File;
use fize\io\Stream;

$stream = new Stream(fopen('https://www.baidu.com', 'r'));
$fso = fopen('../temp/baidu.txt', 'w');
$rst = $stream->copyToStream($fso);
var_dump($rst);

$dest = new File($fso);
var_dump($dest->getContents());
