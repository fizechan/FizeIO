<?php
require_once "../vendor/autoload.php";

use fize\io\File;

$file = new File('../temp/test.txt');
$file->open('a+');
$len = $file->puts('这是我要写入的字符串');
var_dump($len);
$file->close();