<?php
require_once "../vendor/autoload.php";

use fize\io\Directory;

define('PATH_ROOT', dirname(__FILE__));

$result = Directory::chdir(PATH_ROOT . '/data/dir0/测试目录1');
var_dump($result);

$result = Directory::createFile('test22');
var_dump($result);

$result = Directory::createFile('test22.txt');
var_dump($result);

$result = Directory::createFile('测试文件22.txt');
var_dump($result);

$result = Directory::createFile('./新建文件夹/测试文件22.txt', true);
var_dump($result);
