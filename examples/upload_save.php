<?php
require_once "../vendor/autoload.php";

use fize\io\Upload;

$upload = new Upload('upfile');
$file = $upload->save();

//还没实际进行上传操作

$result = [
    'errcode' => 0,
    'errmsg'  => '',
    'data' => [
        'path' => $file->realpath()
    ]
];

echo json_encode($result);
