<?php
require_once "../vendor/autoload.php";

use fize\io\File;

$file = new File('../temp/test.txt', 'r+');

//使用SPL方式操作
$len = $file->fwrite("1234567890\r\n");

//使用file函数操作
$file->open('w');
$file->write("1234567890\r\n");

$file->close();