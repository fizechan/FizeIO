<?php
require_once "../vendor/autoload.php";

use fize\io\File;

$file = new File('../temp/test.txt', 'r');
$file->open();
$content1 = $file->gets();
var_dump($content1);
$file->rewind();
$content2 = $file->gets();
var_dump($content2);

// $content1 == $content2

$file->close();