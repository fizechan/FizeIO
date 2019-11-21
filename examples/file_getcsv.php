<?php
require_once "../vendor/autoload.php";

use fize\io\File;

$file = new File('../temp/data/test.csv');
$file->open();
$csv = $file->getcsv();
var_dump($csv);
$file->close();