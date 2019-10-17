<?php
/** @noinspection PhpComposerExtensionStubsInspection */
require_once "../../vendor/autoload.php";

use fize\io\Ob;

Ob::gzhandler('', 4);
//Ob::start('ob_gzhandler');  //ob_gzhandler在win下不受支持
Ob::start();
echo '1';
echo '2';
echo '3';
echo '4';
Ob::endFlush();