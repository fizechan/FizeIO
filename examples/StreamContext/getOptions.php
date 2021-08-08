<?php
require_once "../../vendor/autoload.php";

use fize\io\StreamContext;

$opts = [
    'http' => [
        'method' => "GET",
        'header' => "Accept-language: en\r\n" .
            "Cookie: foo=bar\r\n"
    ]
];
$context = new StreamContext(StreamContext::getDefault($opts));
$options = $context->getOptions();
var_dump($options);