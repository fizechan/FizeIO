<?php
require_once "../vendor/autoload.php";

use fize\io\Directory;

$root = dirname(__FILE__);
var_dump($root);

$wd0 = Directory::getcwd();
var_dump($wd0);

$dir1 = new Directory("./data/dir1/dir2/测试目录3");  //该文件夹不存在，当前工作目录并不转移
var_dump($dir1);

$wd1 = Directory::getcwd();
var_dump($wd1);

$dir2 = new Directory("./data/dir4/dir5/测试目录6", true);  //当前工作目录转移到"测试目录6"
var_dump($dir2);

$wd2 = Directory::getcwd();
var_dump($wd2);


Directory::mk('./测试目录7/测试目录8');  //创建文件夹并不会转移当前工作目录

$wd3 = Directory::getcwd();
var_dump($wd3);

Directory::mk( $root . '/data/测试目录1/测试目录2');  //绝对路径

$wd4 = Directory::getcwd();
var_dump($wd4);

$wd5 = Directory::getcwd();
var_dump($wd5);