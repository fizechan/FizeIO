<?php
require_once "../vendor/autoload.php";

use fize\io\File;

$file = new File('../temp/test.txt');

//可以使用getSplFileObject()方法取得对应的SPL文件对象，然后进行操作。
$spl = $file->getSplFileObject();
$spl->fwrite('这是写入的内容');