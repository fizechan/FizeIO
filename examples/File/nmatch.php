<?php
require_once "../../vendor/autoload.php";

use fize\io\File;

$file = new File('../temp/data/test.html');
$rst = $file->nmatch('*.html');
var_dump($rst);