<?php
require_once "../../vendor/autoload.php";

use Fize\IO\File;

$file = new File('../temp/data/test.html');
$content = $file->getContents();
var_dump($content);