<?php

namespace Tests;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class TestUpload extends TestCase
{

    /**
     * @var bool
     */
    protected static $seriver = false;

    /**
     * @var Client
     */
    protected $client;

    /**
     * 构造时启动内置服务器用于测试
     * @param null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        if(!self::$seriver) {
            self::$seriver = true;
            $cmd = 'start cmd /k "cd /d %cd%/../examples &&php -S localhost:8123"';
            $pid = popen($cmd, 'r');
            pclose($pid);
            sleep(3);  //待服务器启动
        }

        if(!$this->client) {
            $this->client = new Client([
                'base_uri' => 'http://localhost:8123'
            ]);
        }
    }

    public function test__construct()
    {
        $response = $this->client->request('POST', 'upload___construct.php', [
            'multipart' => [
                [
                    'name'     => 'upfile',
                    'contents' => fopen(__DIR__ . '/../temp/data/image1.jpg', 'r')
                ]
            ]
        ]);

        $body = $response->getBody();
        $json = json_decode($body, true);

        self::assertIsArray($json);
        self::assertEquals(0, $json['errcode']);
    }

    public function testError()
    {
        $response = $this->client->request('POST', 'upload_error.php', [
            'multipart' => [
                [
                    'name'     => 'upfile1',
                    'contents' => fopen(__DIR__ . '/../temp/data/image1.jpg', 'r')
                ],
                [
                    'name'     => 'upfile2',
                    'contents' => fopen(__DIR__ . '/../temp/data/video1.mp4', 'r')
                ]
            ]
        ]);

        $body = $response->getBody();

        //echo $body;

        $json = json_decode($body, true);

        self::assertIsArray($json);
        self::assertEquals(0, $json['errcode']);

        var_dump($json['data']['error2']);

        self::assertEmpty($json['data']['error1']);
        self::assertNotEmpty($json['data']['error2']);
    }

    public function testPath()
    {
        $response = $this->client->request('POST', 'upload_path.php', [
            'multipart' => [
                [
                    'name'     => 'upfile1',
                    'contents' => fopen(__DIR__ . '/../temp/data/image1.jpg', 'r')
                ],
                [
                    'name'     => 'upfile2',
                    'contents' => fopen(__DIR__ . '/../temp/data/video1.mp4', 'r')
                ]
            ]
        ]);

        $body = $response->getBody();

        //echo $body;

        $json = json_decode($body, true);

        self::assertIsArray($json);
        self::assertEquals(0, $json['errcode']);

        var_dump($json['data']['path2']);

        self::assertNotEmpty($json['data']['path1']);
        self::assertEmpty($json['data']['path2']);
    }

    public function testSave()
    {
        $response = $this->client->request('POST', 'upload_save.php', [
            'multipart' => [
                [
                    'name'     => 'upfile',
                    'contents' => fopen(__DIR__ . '/../temp/data/image1.jpg', 'r')
                ]
            ]
        ]);

        $body = $response->getBody();

        //echo $body;

        $json = json_decode($body, true);

        self::assertIsArray($json);
        self::assertEquals(0, $json['errcode']);

        var_dump($json['data']['path']);

        self::assertNotEmpty($json['data']['path']);
    }

    public function testInit()
    {
        $response = $this->client->request('POST', 'upload_config.php', [
            'multipart' => [
                [
                    'name'     => 'upfile1',
                    'contents' => fopen(__DIR__ . '/../temp/data/image1.jpg', 'r')
                ],
                [
                    'name'     => 'upfile2',
                    'contents' => fopen(__DIR__ . '/../temp/data/image1.jpg', 'r')
                ]
            ]
        ]);

        $body = $response->getBody();

        //echo $body;

        $json = json_decode($body, true);

        self::assertIsArray($json);
        self::assertEquals(0, $json['errcode']);

        var_dump($json['data']['dir1']);
        var_dump($json['data']['dir2']);
        var_dump($json['data']['path1']);
        var_dump($json['data']['path2']);

        self::assertNotEquals($json['data']['dir1'], $json['data']['dir2']);
    }

    public function testSingle()
    {
        $response = $this->client->request('POST', 'upload_single.php', [
            'multipart' => [
                [
                    'name'     => 'upfile',
                    'contents' => fopen(__DIR__ . '/../temp/data/image1.jpg', 'r')
                ]
            ]
        ]);

        $body = $response->getBody();

        //echo $body;

        $json = json_decode($body, true);

        self::assertIsArray($json);
        self::assertEquals(0, $json['errcode']);

        var_dump($json['data']['dir']);
        var_dump($json['data']['path']);
        self::assertNotEmpty($json['data']['path']);
    }

    public function testMultiple()
    {
        $response = $this->client->request('POST', 'upload_multiple.php', [
            'multipart' => [
                [
                    'name'     => 'upfiles[]',
                    'contents' => fopen(__DIR__ . '/../temp/data/image1.jpg', 'r')
                ],
                [
                    'name'     => 'upfiles[]',
                    'contents' => fopen(__DIR__ . '/../temp/data/image2.jpg', 'r')
                ],
                [
                    'name'     => 'upfiles[]',
                    'contents' => fopen(__DIR__ . '/../temp/data/water.png', 'r')
                ]
            ]
        ]);

        $body = $response->getBody();

        //echo $body;

        $json = json_decode($body, true);

        self::assertIsArray($json);
        self::assertEquals(0, $json['errcode']);

        var_dump($json['data']['paths']);
        var_dump($json['data']['errors']);
        $errors = implode('', $json['data']['errors']);

        self::assertEquals(3, $json['data']['count']);

        self::assertNotEmpty($json['data']['paths']);
        self::assertEmpty($errors);
    }
}
