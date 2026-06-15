<?php
require_once "../../vendor/autoload.php";

use Fize\IO\FileF;

$file = new FileF();
$file->open('../temp/test.txt', 'r');
$content = $file->gets(11);
var_dump($content);
echo strlen($content);  //10
$file->close();