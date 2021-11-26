<?php
require_once "../../vendor/autoload.php";

use Fize\Crypt\Json;
use Fize\IO\Upload;

$upload = new Upload('upfile');
$file = $upload->save();

//还没实际进行上传操作

$result = [
    'errcode' => 0,
    'errmsg'  => '',
    'data' => [
        'path' => $file->getRealPath()
    ]
];

echo Json::encode($result);
