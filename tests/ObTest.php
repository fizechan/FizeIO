<?php
/** @noinspection HtmlUnknownTarget */

use PHPUnit\Framework\TestCase;
use fize\io\Ob;
use GuzzleHttp\Client;

class ObTest extends TestCase
{

    public function testClean()
    {
        Ob::start();
        echo '1';
        echo '2';
        Ob::clean();
        echo '3';
        echo '4';
        $ob = Ob::getClean();
        self::assertEquals($ob, '34');
    }

    public function testEndClean()
    {
        Ob::start();
        echo '1';
        echo '2';
        Ob::endClean();
        Ob::start();
        echo '3';
        echo '4';
        $ob = Ob::getClean();
        self::assertEquals($ob, '34');
    }

    public function testEndFlush()
    {
        Ob::start();
        echo '1';
        echo '2';
        Ob::endFlush();
        Ob::start();  //要重新打开了
        echo '3';
        echo '4';
        $ob = Ob::getClean();
        self::assertEquals($ob, '34');
    }

    public function testFlush()
    {
        Ob::start();
        echo '1';
        echo '2';
        Ob::flush();
        echo '3';
        echo '4';
        $ob = Ob::getClean();
        self::assertEquals($ob, '34');
    }

    public function testGetClean()
    {
        Ob::start();
        echo '1';
        echo '2';
        Ob::endClean();
        Ob::start();
        echo '3';
        echo '4';
        $ob = Ob::getClean();
        self::assertEquals($ob, '34');
    }

    public function testGetContents()
    {
        Ob::start();
        echo '1';
        echo '2';
        echo '3';
        echo '4';
        $ob = Ob::getContents();
        Ob::endClean();
        self::assertEquals($ob, '1234');
    }

    public function testGetFlush()
    {
        Ob::start();
        echo '1';
        echo '2';
        echo '3';
        echo '4';
        $ob = Ob::getFlush();  //调用后缓冲区自动关闭了
        self::assertEquals($ob, '1234');
    }

    public function testGetLength()
    {
        Ob::start();
        echo '1';
        echo '2';
        echo '3';
        echo '4';
        $ob_length = Ob::getLength();
        Ob::endClean();
        self::assertEquals($ob_length, 4);
    }

    public function testGetLevel()
    {
        Ob::start();
        echo '1';
        echo '2';
        echo '3';
        echo '4';
        $ob_level = Ob::getLevel();
        Ob::endClean();
        self::assertEquals($ob_level, 2);
    }

    public function testGetStatus()
    {
        Ob::start();
        echo '1';
        echo '2';
        echo '3';
        echo '4';
        Ob::endClean();
        echo '5';
        $statuses = Ob::getStatus(true);
        var_dump($statuses);

        self::assertIsArray($statuses);
    }

    /**
     * @todo 该测试实际未进行
     */
    public function testGzhandler()
    {
        $cmd = 'start cmd /k "cd /d %cd%/../examples &&php -S localhost:8123"';
        $pid = popen($cmd, 'r');
        pclose($pid);
        sleep(3);  //待服务器启动

        $client = new Client([
            'base_uri' => 'http://localhost:8123'
        ]);

        $response = $client->get('ob_gzhandler.php');

        $body = $response->getBody();
        self::assertEquals((string)$body, '1234');
    }

    public function testImplicitFlush()
    {
        Ob::start();
        Ob::implicitFlush(true);
        echo '1';
        echo '2';
        echo '3';
        echo '4';
        Ob::endClean();
        self::assertTrue(true);
    }

    public function testListHandlers()
    {
        $handlers = Ob::listHandlers();
        var_dump($handlers);
        self::assertIsArray($handlers);
    }

    public function testStart()
    {
        $result = Ob::start();
        Ob::implicitFlush(true);
        echo '1';
        echo '2';
        echo '3';
        echo '4';
        Ob::endClean();
        self::assertTrue($result);
    }

    /**
     * @todo file.php并没有如文档缩写的变为file.php?var=value
     */
    public function testOutputAddRewriteVar()
    {
        //Ob::start();

        Ob::outputAddRewriteVar('var', 'value');

        // some links
        echo '<a href="file.php">link</a> <a href="http://example.com">link2</a>';

        // a form
        echo '<form action="#" method="post"> <input type="text" name="var2" /> </form>';

        Ob::endFlush();

        self::assertTrue(true);

    }

    public function testOutputResetRewriteVars()
    {
        Ob::outputAddRewriteVar('var1', 'value1');
        echo '<form action="#" method="post"> <input type="text" name="var2" /> </form>';
        Ob::flush();
        Ob::outputResetRewriteVars();
        echo '<form action="#" method="post"> <input type="text" name="var2" /> </form>';
        Ob::endFlush();
        self::assertTrue(true);
    }
}
