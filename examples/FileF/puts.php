<?php
require_once "../../vendor/autoload.php";

use Fize\IO\FileF;

$file = new FileF();
$file->open('../temp/test.txt', 'a+');
$len = $file->puts('这是我要写入的字符串');
var_dump($len);
$file->close();