<?php
require_once "../vendor/autoload.php";

use fize\io\File;

$file = new File('../temp/test.txt');

$result = $file->delete();
var_dump($result);

$file->close();