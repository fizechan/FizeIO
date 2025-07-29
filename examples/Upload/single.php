<?php
require_once "../../vendor/autoload.php";

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

echo json_encode($result);
