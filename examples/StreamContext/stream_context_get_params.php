<?php
require_once "../../vendor/autoload.php";

use fize\io\Stream;

$stream = new Stream(Stream::contextCreate());
$params = ["notification" => "stream_notification_callback"];
$stream->contextSetParams($params);

$params = $stream->contextGetParams();
var_dump($params);