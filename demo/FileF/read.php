<?php
require_once "../../vendor/autoload.php";

use Fize\IO\FileF;

$file = new FileF();
$file->open('../temp/test.txt', 'r');
$content = $file->read(1024);
var_dump($content);
$file->close();