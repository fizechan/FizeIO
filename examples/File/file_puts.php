<?php
require_once "../../vendor/autoload.php";

use fize\io\FileF;

$file = new FileF('../temp/test.txt', 'a+');
$len = $file->puts('这是我要写入的字符串');
var_dump($len);
$file->close();