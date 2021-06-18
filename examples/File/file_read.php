<?php
require_once "../vendor/autoload.php";

use fize\io\File;

$file = new File('../temp/test.txt');
$file->open('r');
$content = $file->read(1024);
var_dump($content);
$file->close();