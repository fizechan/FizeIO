<?php
require_once "../vendor/autoload.php";

use fize\io\File;

$file = new File('../temp/test.txt', 'a+');
$file->open();
$file->write("123456");
$size1 = $file->size();
$file->write("789000");
$size2 = $file->size();

//$size1 此时等于 $size2

$file->clearstatcache();
$size3 = $file->size();

//清除缓存后 $size2 等于 $size3

$file->close();