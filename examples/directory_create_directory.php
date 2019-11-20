<?php
require_once "../vendor/autoload.php";

use fize\io\Directory;

define('PATH_ROOT', dirname(__FILE__));

$result = Directory::ch(PATH_ROOT . '/data/dir0/测试目录1');
var_dump($result);

$result = Directory::createDirectory('新建文件夹2');
var_dump($result);

$result = Directory::createDirectory('./新建文件夹3/新建文件夹33/新建文件夹333', 0777, true);
var_dump($result);