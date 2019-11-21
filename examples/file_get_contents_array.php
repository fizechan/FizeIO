<?php
require_once "../vendor/autoload.php";

use fize\io\File;

$file = new File('../temp/data/test.html');
$lines = $file->getContentsArray();
var_dump($lines);