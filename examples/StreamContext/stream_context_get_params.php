<?php
require_once "../../vendor/autoload.php";

use fize\io\StreamContext;

$context = new StreamContext();
$context->create();
$params = ["notification" => "stream_notification_callback"];
$context->setParams($params);

$params = $context->getParams();
var_dump($params);