<?php
require_once "../../vendor/autoload.php";

use Fize\Crypt\Json;
use Fize\IO\Upload;

$upload = new Upload('upfile');

//还没实际进行上传操作

$result = [
    'errcode' => 0,
    'errmsg'  => '',
];

echo Json::encode($result);
