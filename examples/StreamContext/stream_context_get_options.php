<?php
require_once "../vendor/autoload.php";

use fize\io\Stream;

$opts = [
    'http' => [
        'method' => "GET",
        'header' => "Accept-language: en\r\n" .
            "Cookie: foo=bar\r\n"
    ]
];
$stream = new Stream(Stream::contextGetDefault($opts));
$options = $stream->contextGetOptions();
var_dump($options);