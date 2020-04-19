<?php
require_once "../vendor/autoload.php";

use fize\io\Directory;

define('PATH_ROOT', dirname(__FILE__));

$result = Directory::chdir(PATH_ROOT . '/data/dir0');
var_dump($result);

$result = Directory::deleteDirectory('test1');
var_dump($result);

$result = Directory::deleteDirectory('test3', true);
var_dump($result);

$result = Directory::deleteDirectory('./测试目录2/测试目录11', true);
var_dump($result);
