<?php

namespace fize\io;

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
        return disk_free_space($this->directory);
    }

    /**
     * 返回总大小
     * @return float 字节数
     */
    public function totalSpace(): float
    {
        return disk_total_space($this->directory);
    }

}