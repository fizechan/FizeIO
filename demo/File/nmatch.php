<?php
require_once "../../vendor/autoload.php";

use Fize\IO\File;

$file = new File('../temp/data/test.html');
$rst = $file->nmatch('*.html');
var_dump($rst);