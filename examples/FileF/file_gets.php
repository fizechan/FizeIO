<?php
require_once "../../vendor/autoload.php";

use fize\io\FileF;

$file = new FileF('../temp/test.txt', 'r');
$content = $file->gets(11);
var_dump($content);
echo strlen($content);  //10
$file->close();