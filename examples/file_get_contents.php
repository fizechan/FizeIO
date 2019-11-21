<?php
require_once "../vendor/autoload.php";

use fize\io\File;

$file = new File('../temp/data/test.html');
$content = $file->getContents();
var_dump($content);