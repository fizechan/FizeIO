<?php
require_once "../../vendor/autoload.php";

use fize\io\FileF;

$file = new FileF('../temp/data/test.html', 'r');
$content = $file->getss();  // getss自PHP7.3开始不建议使用
var_dump($content);
$file->close();