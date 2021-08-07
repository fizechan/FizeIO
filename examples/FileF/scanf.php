<?php
require_once "../../vendor/autoload.php";

use fize\io\FileF;

$file = new FileF();
$file->open('../temp/test.txt', 'r');
$info = $file->scanf('%s');
var_dump($info);
$file->close();