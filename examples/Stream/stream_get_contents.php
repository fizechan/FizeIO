<?php
require_once "../vendor/autoload.php";

use fize\io\Stream;

$stream = new Stream('https://www.baidu.com', 'r');
$content = $stream->getContents();
var_dump($content);
