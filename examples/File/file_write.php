<?php
require_once "../../vendor/autoload.php";

use fize\io\FileF;

$file = new FileF();
$file->open('../temp/test.txt', 'w');
$len = $file->write("1234567890\r\n");
var_dump($len);
$file->close();