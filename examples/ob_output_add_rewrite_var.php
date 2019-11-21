<?php
require_once "../vendor/autoload.php";

use fize\io\Ob;


Ob::start();

Ob::outputAddRewriteVar('var1', 'value1');

// some links
echo '<a href="ob_output_add_rewrite_var.php">link</a> <a href="http://example.com">link2</a>';

// a form
echo '<form action="#" method="post"> <input type="text" name="var2" /> </form>';

Ob::endFlush();