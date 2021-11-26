<?php
require_once "../../vendor/autoload.php";

use Fize\IO\Stream;

$stream = new Stream();
$stream->open('https://www.baidu.com', 'r');
$line1 = $stream->getLine(100);
var_dump($line1);

$line2 = $stream->getLine(100, ">");
var_dump($line2);
