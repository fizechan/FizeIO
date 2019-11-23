<?php
require_once "../vendor/autoload.php";

use fize\io\Stream;
use fize\io\File;

$opts = [
    'http' => [
        'method' => "GET",
        'header' => "Accept-language: en\r\n" .
            "Cookie: foo=bar\r\n"
    ]
];

$context = Stream::contextCreate();
$stream = new Stream($context);
$rst = $stream->contextSetOption($opts);
var_dump($rst);

$fp = new File('https://www.baidu.com');
$fp->open('r', false, $stream->get());
$fp->passthru();
$fp->close();