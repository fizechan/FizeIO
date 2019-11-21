<?php
require_once "../vendor/autoload.php";

use fize\io\File;

$file = new File('../temp/data/test.html');
$file->putContents("\n<h2>很好很强大</h2>");  //覆盖

$len = $file->putContents("\n<h2>很好很强大2</h2>", FILE_APPEND);  //追加内容
var_dump($len);