<?php
require_once "../vendor/autoload.php";

use fize\io\File;

$file = new File('../temp/test.txt');
$file->open('r');
$content = $file->gets(11);
var_dump($content);
echo strlen($content);  //10
$file->close();