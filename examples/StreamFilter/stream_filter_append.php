<?php
require_once "../../vendor/autoload.php";

use fize\io\FileF;
use fize\io\StreamFilter;


StreamFilter::append("string.rot13", STREAM_FILTER_WRITE);
$fp = new FileF('../temp/testStreamFilterAppend.txt', 'w+');
$fp->write("This is a test\n");
$fp->rewind();
$fp->passthru();
$fp->close();
