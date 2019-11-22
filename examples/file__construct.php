<?php
require_once "../vendor/autoload.php";

use fize\io\File;

$file = new File('../temp/test.txt', 'w');
$file->open();
$file->write("1234567890\r\n");

$file->close();