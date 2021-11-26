<?php
require_once "../../vendor/autoload.php";

use Fize\IO\File;

$file = new File('../temp/test.txt', 'a+');
$file->fwrite("123456");
$size1 = $file->getSize();
$file->fwrite("789000");
$size2 = $file->getSize();

//$size1 此时等于 $size2

$file->clearstatcache();
$size3 = $file->getSize();

//清除缓存后 $size2 等于 $size3
