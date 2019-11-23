<?php
require_once "../vendor/autoload.php";

use fize\io\Stream;
use fize\io\File;

$src = new File('https://www.baidu.com', 'r');
$src->open();
$stream = new Stream($src->getStream());
$content = $stream->getContents();
var_dump($content);