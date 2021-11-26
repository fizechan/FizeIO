<?php
require_once "../../vendor/autoload.php";

use Fize\IO\FileF;

$file = new FileF();
$file->open('../temp/data/test.html', 'r');
$content = $file->getss();  // getss自PHP7.3开始不建议使用
var_dump($content);
$file->close();