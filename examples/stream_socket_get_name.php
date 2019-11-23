<?php
require_once "../vendor/autoload.php";

use fize\io\Stream;
use fize\io\File;

$src = new File('https://www.baidu.com', 'r');
$src->open();
$stream = new Stream($src->getStream());

//远程
$rst = $stream->socketGetName(true);
var_dump($rst);

//本地
$rst = $stream->socketGetName(false);
var_dump($rst);

$src->close();