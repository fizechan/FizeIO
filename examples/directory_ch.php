<?php
require_once "../vendor/autoload.php";

use fize\io\Directory;

define('PATH_ROOT', dirname(__FILE__));

$dir = new Directory("./data");
$dir->close();
$path1 = Directory::getcwd();
var_dump($path1);

$result = Directory::ch(PATH_ROOT . '/data/dir0/测试目录1');

$path2 = Directory::getcwd();
var_dump($path1);
