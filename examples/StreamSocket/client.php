<?php
require_once "../../vendor/autoload.php";

use Fize\IO\FileF;
use Fize\IO\StreamSocket;

$fp = StreamSocket::client("tcp://www.baidu.com:80", $errno, $errstr, 30);
if ($fp === false) {
    echo "$errstr ($errno)<br />\n";
} else {
    $ffp = new FileF($fp);
    $ffp->write("GET / HTTP/1.0\r\nHost: www.baidu.com\r\nAccept: */*\r\n\r\n");
    while (!$ffp->eof()) {
        echo $ffp->gets(1024);
    }
    $ffp->close();
}