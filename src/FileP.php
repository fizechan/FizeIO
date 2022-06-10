<?php

namespace Fize\IO;

use RuntimeException;

/**
 * 进程文件
 */
class FileP extends FileAbstract
{

    /**
     * 析构
     */
    public function __destruct()
    {
        if ($this->stream && get_resource_type($this->stream) == 'stream') {
            $this->close();
        }
    }

    /**
     * 关闭文件
     * @return int
     */
    public function close(): int
    {
        if (!is_resource($this->stream)) {
            return true;
        }
        $result = pclose($this->stream);
        if ($result != -1) {
            $this->stream = null;  // 如果正确关闭了则清空当前对象的file_resource
        }
        return $result;
    }

    /**
     * 打开进程文件
     * @param string $command 命令
     * @param string $mode    模式
     */
    public function open(string $command, string $mode)
    {
        if ($this->stream) {
            throw new RuntimeException('The original stream has not been closed');
        }
        $this->stream = popen($command, $mode);
    }
}