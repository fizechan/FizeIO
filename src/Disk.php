<?php

namespace Fize\IO;

use RuntimeException;

/**
 * 磁盘
 */
class Disk extends Directory
{

    /**
     * @var string 指定目录或盘符
     */
    protected $directory;

    /**
     * 初始化
     * @param string $directory 指定目录或盘符
     */
    public function __construct(string $directory)
    {
        $this->directory = $directory;
        parent::__construct($directory);
    }

    /**
     * 返回可用空间
     * @return float 可用的字节数
     */
    public function freeSpace(): float
    {
        $number = disk_free_space($this->directory);
        if ($number === false) {
            throw new RuntimeException('error on disk_free_space');
        }
        return $number;
    }

    /**
     * 返回总大小
     * @return float 字节数
     */
    public function totalSpace(): float
    {
        $number = disk_total_space($this->directory);
        if ($number === false) {
            throw new RuntimeException('error on disk_total_space');
        }
        return $number;
    }

}