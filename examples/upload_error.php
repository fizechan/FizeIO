<?php
/** @noinspection PhpComposerExtensionStubsInspection */
require_once "../vendor/autoload.php";

use fize\io\Upload;

$upload1 = new Upload('upfile1');
$upload1->save();

$upload2 = new Upload('upfile2');
$upload2->save();

//还没实际进行上传操作

$result = [
    'errcode' => 0,
    'errmsg'  => '',
    'data' => [
        'error1' => $upload1->error(),
        'error2' => $upload2->error()
    ]
];

echo json_encode($result);