<?php
require_once "../vendor/autoload.php";

use fize\io\File;

$file = new File('../temp/test.txt', 'r');
$file->open();
$rst = $file->eof();
var_dump($rst);  //false
$file->read(1024);  //内容实际短于1024
$rst = $file->eof();
var_dump($rst);  //true