<?php
require_once "../vendor/autoload.php";

use fize\io\Directory;

$root = dirname(__FILE__);
var_dump($root);

$wd = Directory::getcwd();
var_dump($wd);

$dir1 = new Directory("./data/dir1/dir2/测试目录3");
var_dump($dir1);

$wd = Directory::getcwd();
var_dump($wd);

$dir2 = new Directory("./data/dir4/dir5/测试目录6", true);
var_dump($dir2);

$wd = Directory::getcwd();
var_dump($wd);

$result = Directory::mk('./测试目录7/测试目录8');  //当前目录已在测试目录6

$wd = Directory::getcwd();
var_dump($wd);

$result = Directory::mk( $root . '/data/测试目录1/测试目录2');  //绝对路径

$wd = Directory::getcwd();
var_dump($wd);

$wd = Directory::getcwd();
var_dump($wd);