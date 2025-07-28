<?php

namespace Fize\IO;

use RuntimeException;

/**
 * 目录
 */
class Directory
{

    /**
     * @var string 当前目录路径
     */
    private $path;

    /**
     * @var resource 当前目录句柄
     */
    private $handle = null;

    /**
     * 构造函数
     * @param string $path       指定目录路径
     * @param bool   $auto_build 指定的路径不存在时创建
     */
    public function __construct(string $path, bool $auto_build = false)
    {
        $this->path = self::realpath($path, false);
        if ($auto_build) {
            $this->mkdirIfNotExists();
        }
    }

    /**
     * 析构函数
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * 打开当前目录
     */
    public function open()
    {
        $this->handle = opendir($this->path);
    }

    /**
     * 关闭当前目录
     */
    public function close()
    {
        if ($this->handle) {
            closedir($this->handle);
        }
        $this->handle = null;
    }

    /**
     * 遍历当前目录的文件条目
     *
     * 参数 `$func` :
     *   函数参数($item); $item : 条目名称
     * @param callable $func        遍历函数
     * @param bool     $filter_base 是否剔除.和..
     */
    public function read(callable $func, bool $filter_base = false)
    {
        while (($item = readdir($this->handle)) !== false) {
            if ($filter_base && ($item == '.' || $item == '..')) {
                continue;
            }
            $func($item);
        }
    }

    /**
     * 将当前目录流重置到目录的开头。
     */
    public function rewind()
    {
        rewinddir($this->handle);
    }

    /**
     * 清理指定文件夹
     * @param string|null $path 目录路径,不指定则为当前文件夹
     * @return bool
     */
    public function clear(string $path = null): bool
    {
        if (is_null($path)) {
            $path = $this->path;
        }
        $result = true;
        $handle = opendir($path);
        while (false !== ($item = readdir($handle))) {
            clearstatcache();
            if ($item != '.' && $item != '..') {
                if (is_dir("$path/$item")) {
                    $result = $result && $this->clear("$path/$item");
                    $result = $result && rmdir("$path/$item");
                } else {
                    $result = $result && unlink("$path/$item");
                }
            }
        }
        closedir($handle);
        return $result;
    }

    /**
     * 列出路径中的文件和目录 (含.和..)
     * @param int $sorting_order 排序
     * @return array
     */
    public function scan(int $sorting_order = 0): array
    {
        return scandir($this->path, $sorting_order);
    }

    /**
     * 在当前文件夹建立一个具有唯一文件名的文件
     * @param string $prefix 产生临时文件的前缀
     * @return string 返回完整文件路径
     */
    public function tempnam(string $prefix = ''): string
    {
        $tempnam = tempnam($this->path, $prefix);
        if ($tempnam === false) {
            throw new RuntimeException('cannot tempnam');
        }
        return $tempnam;
    }

    /**
     * 创建当前目录
     * @param int      $mode      设置访问权
     * @param bool     $recursive 是否可递归创建多层目录
     * @param resource $context   上下文支持
     * @return bool
     */
    public function create(int $mode = 0775, bool $recursive = false, $context = null): bool
    {
        return mkdir($this->path, $mode, $recursive, $context);
    }

    /**
     * 删除当前文件夹
     * @param bool $force 如果目录不为空时是否强制删除
     * @return bool
     */
    public function delete(bool $force = false): bool
    {
        if (!self::exists($this->path)) {
            return true;
        }
        if ($force) {
            $this->clear();
        }
        return rmdir($this->path);
    }

    /**
     * 创建当前目录
     *
     * 要创建的目录已存在则不进行处理
     */
    private function mkdirIfNotExists()
    {
        if (self::exists($this->path)) {
            return;
        }
        mkdir($this->path, 0775, true);
    }

    /**
     * 判断目录是否存在
     *
     * 该方法在Windows下也严格遵守大小写
     * @param string $path 路径
     * @return bool
     */
    public static function exists(string $path): bool
    {
        if (is_dir($path)) {
            if (strstr(PHP_OS, 'WIN')) {
                if (basename(realpath($path)) != pathinfo($path, PATHINFO_BASENAME)) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    /**
     * 返回规范化的绝对路径名
     * @param string $path  路径
     * @param bool   $check 是否检测路径真实有效
     * @return string
     */
    public static function realpath(string $path, bool $check = true): string
    {
        if ($check) {
            if (!self::exists($path)) {
                throw new RuntimeException('path is not exists: ' . self::realpath($path, false));
            }
            return realpath($path);
        } else {
            if (self::exists($path)) {
                $realpath = realpath($path);
                if ($realpath === false) {
                    throw new RuntimeException('path cannot realpath');
                }
                return $realpath;
            }
            $path = str_replace('\\', '/', $path);
            $last = '';
            while ($path != $last) {
                $last = $path;
                $path = preg_replace('/\/[^\/]+\/\.\.\//', '/', $path);
            }
            $last = '';
            while ($path != $last) {
                $last = $path;
                $path = preg_replace('/([.\/]\/)+/', '/', $path);
            }
            $path = str_replace('/', DIRECTORY_SEPARATOR, $path);
            return rtrim($path, DIRECTORY_SEPARATOR);
        }
    }
}
