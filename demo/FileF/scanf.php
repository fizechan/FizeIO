<?php
require_once "../../vendor/autoload.php";

use Fize\IO\FileF;

$file = new FileF();
$file->open('../temp/test.txt', 'r');
$info = $file->scanf('%s');
var_dump($info);
$file->close();