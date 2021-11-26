<?php
require_once "../../vendor/autoload.php";

use Fize\IO\File;

$file = new File('../temp/test.txt', 'w');
$file->fwrite("1234567890\r\n");