<?php
require_once "../vendor/autoload.php";

use fize\io\File;

$file = new File('../temp/test.txt');
$file->open();
$char = $file->getc();
var_dump($char);
$file->close();