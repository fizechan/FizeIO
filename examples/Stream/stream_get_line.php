<?php
require_once "../vendor/autoload.php";

use fize\io\Stream;

$stream = new Stream('https://www.baidu.com', 'r');
$line1 = $stream->getLine(100);
var_dump($line1);

$line2 = $stream->getLine(100, ">");
var_dump($line2);
