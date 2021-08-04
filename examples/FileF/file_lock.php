<?php
require_once "../../vendor/autoload.php";

use fize\io\FileF;

$file = new FileF('../temp/test.txt', 'w+');
$rst1 = $file->lock(LOCK_EX);  // 进行排它型锁定
if($rst1) {
    $file->write("\n这是我要写入的内容1");
    $file->write("\n这是我要写入的内容2");
    $rst2 = $file->lock(LOCK_UN);  // 释放锁定
}
$file->close();