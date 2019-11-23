<?php
require_once "../vendor/autoload.php";

use fize\io\Stream;
use fize\io\File;

$socket = Stream::socketServer("tcp://0.0.0.0:8000", $errno, $errstr);

if (!$socket) {
    echo "$errstr ($errno)<br />\n";
} else {
    $stream = new Stream($socket);
    $fpsocket = new File($socket);
    while ($conn = $stream->socketAccept(100)) {  //接受连接
        $fpconn = new File($conn);
        $fpconn->write('The local time is ' . date('Y-m-d H:i:s') . "\n");
        $fpconn->close();
    }
    $fpsocket->close();
}