<?php
require_once "../vendor/autoload.php";

use fize\io\File;

$file = new File('../temp/data/test.html');
$file->open('r');
$content = $file->getss();  //getss自PHP7.3开始不建议使用
var_dump($content);
$file->close();