<?php
require_once "../vendor/autoload.php";

use fize\io\Stream;

$stream = new Stream('../temp/testStreamFilterAppend.txt', 'w+');
$stream->filterAppend("string.rot13", STREAM_FILTER_WRITE);
$fp->write("This is a test\n");
$fp->rewind();
$fp->passthru();
$fp->close();
