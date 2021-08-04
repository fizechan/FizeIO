<?php
require_once "../../vendor/autoload.php";

use fize\io\Ob;
use fize\io\Output;

Output::addRewriteVar('var1', 'value1');
echo '<form action="#" method="post"> <input type="text" name="var2" /> </form>';
Ob::flush();
Output::resetRewriteVars();
echo '<form action="#" method="post"> <input type="text" name="var2" /> </form>';
Ob::endFlush();