<?php


namespace fize\io;

use Directory as Dir;

/**
 * 目录操作类
 */
class Directory
{

    /**
     * 当前目录路径
     * @var string
     */
    private $path = '';

    /**
     * 当前目录句柄
     * @var resource
     */
    private $handle = null;

    /**
     * 构造函数
     * @param string $path 指定目录路径
     * @param bool $auto_build 指定的路径不存在时创建
     */
    public function __construct($path, $auto_build = false)
    {
        $this->path = $path;
        if ($auto_build) {
            self::mk($path);
        }
        self::ch($path);
    }

    /**
     * 析构函数
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * 获取当前的Directory对象
     * @param string $path 指定目录路径
     * @return Dir
     * @deprecated 本类已实现了全部的功能，无需再引入系统的Directory类，后续可能删除该接口
     */
    public static function dir($path)
    {
        return dir($path);
    }

    /**
     * 创建目录
     * @param string $path 要创建的目录，已存在则不进行处理
     * @param int $mode 权限
     * @param bool $recursive 是否可递归创建多层目录
     * @return bool
     */
    public static function mk($path, $mode = 0777, $recursive = true)
    {
        if (is_dir($path)) {
            return true;
        } else {
            return mkdir($path, $mode, $recursive);
        }
    }

    /**
     * 打开指定目录
     * @param string $path 指定目录
     */
    public function open($path)
    {
        $this->handle = opendir($path);
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
     * 改变当前目录
     * @param string $path 指定目录
     * @return bool 如果指定目录不存在也返回false
     */
    public static function ch($path)
    {
        if (!is_dir($path)) {
            return false;
        }
        return chdir($path);
    }

    /**
     * 改变根目录
     *
     * 本函数仅在系统支持且运行于 CLI，CGI 或嵌入 SAPI 版本时才能正确工作。
     * 此外本函数还需要 root 权限。
     * 此函数未在 Windows 平台下实现，故也返回false
     * @param string $path 指定目录
     * @return bool
     */
    public static function chroot($path)
    {
        if (!function_exists('chroot')) {
            return false;
        }
        return chroot($path);
    }

    /**
     * 取得当前工作目录
     * @return string
     */
    public static function getcwd()
    {
        return getcwd();
    }

    /**
     * 遍历当前目录的文件条目
     *
     * 参数 `$func` :
     *   函数参数($file); $file : 条目名称
     * @param callable $func 遍历函数
     * @param bool $filter_base 是否剔除.和..
     */
    public function read(callable $func, $filter_base = false)
    {
        while (($file = readdir($this->handle)) !== false) {
            if ($filter_base && ($file == "." || $file == "..")) {
                continue;
            }
            $func($file);
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
     * 列出指定路径中的文件和目录 (含.和..)
     * @param string $path 路径
     * @param int $sorting_order 排序
     * @return array
     */
    public static function scan($path, $sorting_order = 0)
    {
        return scandir($path, $sorting_order);
    }

    /**
     * 创建一个空文件
     * @param string $name 文件路径
     * @param bool $recursive 是否可递归创建多层目录
     * @return bool
     */
    public static function createFile($name, $recursive = false)
    {
        if ($recursive) {
            $dir = dirname($name);
            self::mk($dir);
        }
        return touch($name, time(), null);
    }

    /**
     * 新建目录
     * @param string $name 新建目录名
     * @param int $mode 设置访问权
     * @param bool $recursive 是否可递归创建多层目录
     * @return bool 已有该目录则也返回true
     */
    public static function createDirectory($name, $mode = 0777, $recursive = false)
    {
        return self::mk($name, $mode, $recursive);
    }

    /**
     * 删除指定文件
     *
     * 虽然该方法也可以用来删除文件夹，但不建议如此使用
     * @param string $name 文件路径
     * @return bool 没有该文件则也返回true
     */
    public static function deleteFile($name)
    {
        if (!is_file($name)) {
            return true;
        }
        return unlink($name);
    }

    /**
     * 删除目录及目录下所有文件或删除指定文件(即强制删除非空目录)
     * @param string $path 待删除目录路径
     * @return bool
     */
    private static function deleteDirectoryForce($path)
    {
        if (!is_dir($path)) {
            return true;
        }

        //删除文件夹内的文件及文件夹
        $handle = opendir($path);
        while (false !== ($item = readdir($handle))) {
            clearstatcache();
            if ($item != "." && $item != "..") {
                if (is_dir("{$path}/{$item}")) {
                    self::deleteDirectoryForce("{$path}/{$item}");
                } else {
                    unlink("{$path}/{$item}");
                }
            }
        }
        closedir($handle);
        if ($path == '.') {
            return true;
        }
        return rmdir($path);
    }

    /**
     * 删除指定文件夹
     * @param string $name 要删除的目录名， 可以指定多级目录
     * @param bool $force 如果目录不为空时是否强制删除
     * @return bool
     */
    public static function deleteDirectory($name, $force = false)
    {
        if (!is_dir($name)) {
            return true;
        }
        if ($force) {
            return self::deleteDirectoryForce($name);
        } else {
            return rmdir($name);
        }
    }

    /**
     * 清理当前工作文件夹，即删除里面的所有文件及文件夹
     * @return bool
     */
    public static function clear()
    {
        return self::deleteDirectoryForce('.');
    }

    /**
     * 判断给定文件名是否是一个目录
     * @param string $path 指定目录
     * @return bool
     */
    public static function isDir($path)
    {
        return is_dir($path);
    }

    /**
     * 在当前工作文件夹建立一个具有唯一文件名的文件
     * @param string $prefix 产生临时文件的前缀
     * @return string 返回其文件名
     */
    public static function createTempFile($prefix = '')
    {
        return tempnam(self::getcwd(), $prefix);
    }

    /**
     * 寻找与模式匹配的文件路径
     * @param string $pattern 匹配模式
     * @param int $flags 有效标识
     * @return array
     */
    public static function glob($pattern, $flags = null)
    {
        return glob($pattern, $flags);
    }

    /**
     * 返回目录中的可用空间
     * @param string $directory 指定目录或盘符
     * @return float 可用的字节数，失败时返回false
     */
    public static function diskFreeSpace($directory)
    {
        return disk_free_space($directory);
    }

    /**
     * 返回一个目录的磁盘总大小
     * @param string $directory 指定目录或盘符
     * @return float 字节数，失败时返回false
     */
    public static function diskTotalSpace($directory)
    {
        return disk_total_space($directory);
    }
}