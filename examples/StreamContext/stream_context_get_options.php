<?php
require_once "../../vendor/autoload.php";

use fize\io\Stream;
use fize\io\StreamContext;

$opts = [
    'http' => [
        'method' => "GET",
        'header' => "Accept-language: en\r\n" .
            "Cookie: foo=bar\r\n"
    ]
];
$stream = new StreamContext(StreamContext::GetDefault($opts));
$options = $stream->contextGetOptions();
var_dump($options);