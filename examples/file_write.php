<?php
require_once "../vendor/autoload.php";

use fize\io\File;

$file = new File('../temp/test.txt', 'r+');
$file->open('w');
$len = $file->write("1234567890\r\n");
var_dump($len);
$file->close();