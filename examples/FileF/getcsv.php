<?php
require_once "../../vendor/autoload.php";

use Fize\IO\FileF;

$file = new FileF('../temp/data/test.csv');
$csv = $file->getcsv();
var_dump($csv);
$file->close();