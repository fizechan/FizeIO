<?php
require_once "../../vendor/autoload.php";

use fize\io\FileF;
use fize\io\StreamFilter;

$fp = fopen('../temp/testStreamFilterAppend.txt', 'w+');
StreamFilter::append($fp, "string.rot13", STREAM_FILTER_WRITE);
$fp = new FileF($fp);
$fp->write("This is a test\n");
$fp->rewind();
$fp->passthru();
$fp->close();
