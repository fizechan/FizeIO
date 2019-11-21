<?php
require_once "../vendor/autoload.php";

use fize\io\Ob;


Ob::outputAddRewriteVar('var1', 'value1');
echo '<form action="#" method="post"> <input type="text" name="var2" /> </form>';
Ob::flush();
Ob::outputResetRewriteVars();
echo '<form action="#" method="post"> <input type="text" name="var2" /> </form>';
Ob::endFlush();