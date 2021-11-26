<?php
require_once "../../vendor/autoload.php";

use Fize\IO\Stream;

$stream = new Stream();
$stream->open('https://www.baidu.com', 'r');
$content = $stream->getContents();
var_dump($content);
