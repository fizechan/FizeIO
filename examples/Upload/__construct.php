<?php
require_once "../../vendor/autoload.php";

use fize\crypt\Json;
use fize\io\Upload;

$upload = new Upload('upfile');

//还没实际进行上传操作

$result = [
    'errcode' => 0,
    'errmsg'  => '',
];

echo Json::encode($result);