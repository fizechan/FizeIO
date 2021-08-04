<?php
require_once "../../vendor/autoload.php";

use fize\io\FileF;

$file = new FileF('../temp/test.txt', 'r');
$content = $file->read(1024);
var_dump($content);
$file->close();