<?php
require_once "../../vendor/autoload.php";

use fize\crypt\Json;
use fize\io\Upload;

$infos = Upload::multiple('upfiles');

$paths = [];
$errors = [];
foreach ($infos as $info) {
    $paths[] = $info['path'];
    $errors[] = $info['error'];
}

$result = [
    'errcode' => 0,
    'errmsg'  => '',
    'data' => [
        'count' => count($infos),
        'paths' => $paths,
        'errors' => $errors
    ]
];

echo Json::encode($result);
