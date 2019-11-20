<?php
require_once "../vendor/autoload.php";

use fize\io\Directory;

Directory::ch("./data/dir0");

$result = Directory::glob('*.xlsx');
var_dump($result);