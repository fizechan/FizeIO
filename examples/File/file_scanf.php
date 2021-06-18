<?php
require_once "../vendor/autoload.php";

use fize\io\File;

$file = new File('../temp/test.txt');
$file->open('r');
$info = $file->scanf('%s');
var_dump($info);
$file->close();