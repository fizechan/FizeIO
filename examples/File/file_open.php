<?php
require_once "../../vendor/autoload.php";

use fize\io\FileP;

// 可以使用open方法打开进程文件指针

$file = new FileP();

if (substr(php_uname(), 0, 7) == "Windows"){
    $cmd = "start /B php --version";
} else {
    $cmd = "bash php --version";
}
$file->open($cmd, 'r');
$content = $file->gets();
var_dump($content);
$file->close();