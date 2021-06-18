<?php
require_once "../vendor/autoload.php";

use fize\io\File;

$file = new File('../temp/data/test.csv');
$file->open('a+');
$fields = ['汽车', 'VIN码查询', 'Vin/query', '根据VIN码查询车辆相关信息', '聚合', '通过'];
$len = $file->putcsv($fields);
var_dump($len);
$file->close();