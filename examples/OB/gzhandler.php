<?php
require_once "../../vendor/autoload.php";

use Fize\IO\OB;

//ob_gzhandler在win下不受支持
//Ob::gzhandler('', 4);
//Ob::start(['Ob', 'gzhandler']);
ob_start("ob_gzhandler");
echo '1';
echo '2';
echo '3';
echo '4';
OB::endFlush();