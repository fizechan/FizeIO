<?php
require_once "../vendor/autoload.php";

use fize\io\File;

$file = new File('../temp/test.txt');
$file->open('w');
$file->write("1234567890\r\n");
$file->write("1234567890\r\n");
$file->write("1234567890\r\n");
$result = $file->flush();
$file->close();