<?php
require_once "../vendor/autoload.php";

use fize\io\File;

//可以使用open方法打开进程文件指针

$file = new File('');

if (substr(php_uname(), 0, 7) == "Windows"){
    $cmd = "start /B php --version";
} else {
    $cmd = "bash php --version";
}
$file->open('r', true, $cmd);
$content = $file->gets();
var_dump($content);
$file->close();