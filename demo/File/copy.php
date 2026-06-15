<?php
require_once "../../vendor/autoload.php";

use Fize\IO\File;

$file = new File('../temp/test.txt', 'a+');

//复制到其他文件夹(允许递归创建多层目录)
$result1 = $file->copy('../temp/temp2');
var_dump($result1);

//复制并重命名
$result1 = $file->copy('../temp', 'test2.txt');
var_dump($result1);

//已有文件可以进行覆盖
$result1 = $file->copy('../temp', 'test2.txt', true);
var_dump($result1);