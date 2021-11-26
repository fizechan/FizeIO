<?php

namespace Fize\IO;

use RuntimeException;

/**
 * 原生文件
 */
class FileF extends FileAbstract
{

    /**
     * 析构
     *
     * 清理资源对象，防止内存泄漏
     */
    public function __destruct()
    {
        if ($this->stream && get_resource_type($this->stream) == 'stream') {
            $this->close();
        }
    }

    /**
     * 关闭文件
     * @return bool
     */
    public function close(): bool
    {
        $result = fclose($this->stream);
        if ($result) {
            $this->stream = null;  // 如果正确关闭了则清空当前对象的file_resource
        }
        return $result;
    }

    /**
     * 从文件指针中读入一行并解析 CSV 字段
     *
     * 参数 `$length` :
     *   必须大于 CVS 文件内最长的一行。
     * 参数 `$delimiter` :
     *   （只允许一个字符），默认值为逗号。
     * 参数 `$enclosure` :
     *   （只允许一个字符），默认值为双引号。
     * 参数 `$escape` :
     *   （只允许一个字符），默认是一个反斜杠。
     * @param int    $length    规定行的最大长度
     * @param string $delimiter 设置字段分界符
     * @param string $enclosure 设置字段环绕符
     * @param string $escape    设置转义字符
     * @return array 如果碰到 EOF 则返回 FALSE。
     */
    public function getcsv(int $length = 0, string $delimiter = ",", string $enclosure = '"', string $escape = "\\"): array
    {
        return fgetcsv($this->stream, $length, $delimiter, $enclosure, $escape);
    }

    /**
     * 将行格式化为 CSV 并写入文件指针
     * @param array  $fields      要写入的数组数据
     * @param string $delimiter   分隔符
     * @param string $enclosure   界限符
     * @param string $escape_char 转义符
     * @return int 如果失败返回false
     */
    public function putcsv(array $fields, string $delimiter = ",", string $enclosure = '"', string $escape_char = "\\"): int
    {
        return fputcsv($this->stream, $fields, $delimiter, $enclosure, $escape_char);
    }

    /**
     * 打开文件
     * @param string      $file             文件路径
     * @param string|null $mode             打开模式
     * @param bool        $use_include_path 是否在 include_path 中搜寻文件
     * @param resource    $context          上下文支持
     */
    public function open(string $file, string $mode = null, bool $use_include_path = false, $context = null)
    {
        if ($this->stream) {
            throw new RuntimeException('The original stream has not been closed');
        }
        if (strstr($file, '://') === false || substr($file, 0, 4) == 'file') {
            if (in_array($mode, ['r+', 'w', 'w+', 'a', 'a+', 'x', 'x+'])) {
                $dir = dirname($file);
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }
            }
        }
        $this->stream = fopen($file, $mode, $use_include_path, $context);
    }
}