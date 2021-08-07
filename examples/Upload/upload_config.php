<?php
require_once "../../vendor/autoload.php";

use fize\io\Upload;

$info1 = Upload::single('upfile1');

$config = [
    'rule'    => 'md5'
];
Upload::config($config);

$info2 = Upload::single('upfile2');

$result = [
    'errcode' => 0,
    'errmsg'  => '',
    'data' => [
        'dir1' => dirname($info1['path']),
        'path1' => $info1['path'],
        'dir2' => dirname($info2['path']),
        'path2' => $info2['path'],
    ]
];

echo json_encode($result);
