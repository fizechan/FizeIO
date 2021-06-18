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

$context = Stream::contextCreate($opts);
var_dump($context);

$fp = new File('https://www.baidu.com');
$fp->open('r', false, $context);
$fp->passthru();
$fp->close();