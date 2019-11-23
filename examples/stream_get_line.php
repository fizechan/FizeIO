<?php
require_once "../vendor/autoload.php";

use fize\io\Stream;
use fize\io\File;

$src = new File('https://www.baidu.com', 'r');
$src->open();
$stream = new Stream($src->getStream());
$line1 = $stream->getLine(100);
var_dump($line1);

$line2 = $stream->getLine(100, ">");
var_dump($line2);
