<?php
require_once "../../vendor/autoload.php";

use fize\io\Stream;

$stream = new Stream();
$stream->open('../temp/testStreamFilterRemove.txt', 'w+');
$rst1 = $stream->isLocal();
var_dump($rst1);

$rst2 = $stream->isLocal('https://www.baidu.com');
var_dump($rst2);
