<?php
require_once "../../vendor/autoload.php";

use fize\io\StreamSocket;
use fize\io\FileF;

$socket = StreamSocket::server("tcp://0.0.0.0:8000", $errno, $errstr);

if (!$socket) {
    echo "$errstr ($errno)<br />\n";
} else {
    $stream = new StreamSocket($socket);
    $fpsocket = new FileF();
    $fpsocket->set($socket);
    while ($conn = $stream->accept($socket, 100)) {  // 接受连接
        $fpconn = new FileF();
        $fpconn->set($conn);
        $fpconn->write('The local time is ' . date('Y-m-d H:i:s') . "\n");
        $fpconn->close();
    }
    $fpsocket->close();
}