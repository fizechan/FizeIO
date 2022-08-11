<?php
require_once "../../vendor/autoload.php";

use Fize\Codec\Json;
use Fize\IO\Upload;

$info = Upload::single('upfile');

$result = [
    'errcode' => 0,
    'errmsg'  => '',
    'data' => [
        'dir' => dirname($info['path']),
        'path' => $info['path'],
    ]
];

echo Json::encode($result);
