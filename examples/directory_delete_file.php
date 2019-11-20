<?php
require_once "../vendor/autoload.php";

use fize\io\Directory;

define('PATH_ROOT', dirname(__FILE__));

$result = Directory::ch(PATH_ROOT . '/data/dir0/测试目录1');
var_dump($result);

$result = Directory::deleteFile('test22');
var_dump($result);

$result = Directory::deleteFile('./新建文件夹/测试文件22.txt');
var_dump($result);