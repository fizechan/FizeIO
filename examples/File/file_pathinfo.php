<?php
require_once "../vendor/autoload.php";

use fize\io\File;

$file = new File('../temp/test.txt', 'r');
$info = $file->pathinfo();
var_dump($info);  //返回数组

$dir = $file->pathinfo(PATHINFO_DIRNAME);
var_dump($dir);  //返回所在文件夹