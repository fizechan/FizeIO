<?php
require_once "../vendor/autoload.php";

use fize\io\Directory;


$dir = new Directory("./data/dir0");
$dir->open('.');

//遍历文件夹
echo "---1---<br/>\r\n";
$dir->read(function($file){
    echo "{$file}<br/>\r\n";
});

//指针重置
$dir->rewind();

echo "---2---<br/>\r\n";
$dir->read(function($file){
    echo "{$file}<br/>\r\n";
});

//关闭目录
$dir->close();