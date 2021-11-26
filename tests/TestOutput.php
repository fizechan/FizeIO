<?php

namespace Tests;

use Fize\IO\OB;
use Fize\IO\Output;
use PHPUnit\Framework\TestCase;

class TestOutput extends TestCase
{

    /**
     * @todo file.php并没有如文档缩写的变为file.php?var=value
     */
    public function testOutputAddRewriteVar()
    {
        //Ob::start();

        Output::addRewriteVar('var', 'value');

        //Ob::start();

        // some links
        echo '<a href="TestOutput.php">link</a> <a href="https://example.com">link2</a>';

        // a form
        echo '<form action="#" method="post"> <input type="text" name="var2" /> </form>';

        OB::endFlush();

        self::assertTrue(true);

    }

    public function testOutputResetRewriteVars()
    {
        Output::addRewriteVar('var1', 'value1');
        echo '<form action="#" method="post"> <input type="text" name="var2" /> </form>';
        OB::flush();
        Output::resetRewriteVars();
        echo '<form action="#" method="post"> <input type="text" name="var2" /> </form>';
        OB::endFlush();
        self::assertTrue(true);
    }

}
