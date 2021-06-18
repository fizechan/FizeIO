<?php
require_once "../vendor/autoload.php";

use fize\io\Stream;

$server = Stream::socketServer("tcp://0.0.0.0:8000", $errno, $errstr);

if (!$server) {
    echo "$errstr ($errno)<br />\n";
} else {
    $server = new Stream($server);
    $socket = $server->socketAccept();
    $socket = new Stream($socket);

    /* Grab a packet (1500 is a typical MTU size) of OOB data */
    echo "Received Out-Of-Band: '" . $socket->socketRecvfrom(1500, STREAM_OOB) . "'\n";

    /* Take a peek at the normal in-band data, but don't consume it. */
    echo "Data: '" . $socket->socketRecvfrom(1500, STREAM_PEEK) . "'\n";

    /* Get the exact same packet again, but remove it from the buffer this time. */
    echo "Data: '" . $socket->socketRecvfrom(1500) . "'\n";
}