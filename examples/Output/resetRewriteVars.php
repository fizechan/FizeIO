<?php
require_once "../../vendor/autoload.php";

use Fize\IO\OB;
use Fize\IO\Output;

Output::addRewriteVar('var1', 'value1');
echo '<form action="#" method="post"> <input type="text" name="var2" /> </form>';
OB::flush();
Output::resetRewriteVars();
echo '<form action="#" method="post"> <input type="text" name="var2" /> </form>';
OB::endFlush();