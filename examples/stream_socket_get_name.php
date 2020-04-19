<?php
require_once "../vendor/autoload.php";

use fize\io\Stream;

$stream = new Stream('https://www.baidu.com', 'r');

//远程
$rst = $stream->socketGetName(true);
var_dump($rst);

//本地
$rst = $stream->socketGetName(false);
var_dump($rst);
