<?php
require_once "../../vendor/autoload.php";

use fize\io\Directory;

$dir = new Directory("./data/dir0");
$dir->open();

echo "---1---<br/>\r\n";
$dir->read(function($file){
    echo "{$file}<br/>\r\n";
});

$dir->rewind();

echo "---2---<br/>\r\n";
$dir->read(function($file){
    echo "{$file}<br/>\r\n";
});

$dir->close();
