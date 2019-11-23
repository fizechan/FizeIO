<?php
require_once "../vendor/autoload.php";

use fize\io\Stream;
use fize\io\File;

$src = new File('../temp/testStreamFilterRemove.txt', 'w+');
$src->open();
$stream = new Stream($src->getStream());
$rst1 = $stream->isLocal();
var_dump($rst1);

$rst2 = $stream->isLocal('https://www.baidu.com');
var_dump($rst2);
