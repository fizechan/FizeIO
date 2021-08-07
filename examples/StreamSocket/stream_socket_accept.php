<?php
require_once "../../vendor/autoload.php";

use fize\io\StreamSocket;

$socket = StreamSocket::server("tcp://0.0.0.0:8000", $errno, $errstr);

if (!$socket) {
    echo "$errstr ($errno)<br />\n";
} else {
    while ($fpconn = $socket->accept(100)) {  // 接受连接
        $fpconn->write('The local time is ' . date('Y-m-d H:i:s') . "\n");
        $fpconn->close();
    }
    $socket->close();
}