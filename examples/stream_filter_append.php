<?php
require_once "../vendor/autoload.php";

use fize\io\Stream;
use fize\io\File;

$fp = new File('../temp/testStreamFilterAppend.txt', 'w+');
$fp->open();
$stream = new Stream($fp->getStream());
$stream->filterAppend("string.rot13", STREAM_FILTER_WRITE);
$fp->write("This is a test\n");
$fp->rewind();
$fp->passthru();
$fp->close();