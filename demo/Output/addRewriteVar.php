<?php
require_once "../../vendor/autoload.php";

use Fize\IO\OB;
use Fize\IO\Output;


OB::start();

Output::addRewriteVar('var1', 'value1');

// some links
echo '<a href="addRewriteVar.php">link</a> <a href="https://example.com">link2</a>';

// a form
echo '<form action="#" method="post"> <input type="text" name="var2" /> </form>';

OB::endFlush();