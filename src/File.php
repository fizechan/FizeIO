<?php

namespace Fize\IO;

use RuntimeException;
use SplFileObject;

/**
 * 文件
 */
class File extends SplFileObject
{

    /**
     * @var string 当前文件完整路径
     */
    private $path;

    /**
     * 构造
     * @param string   $filename       文件路径
     * @param string   $mode           打开模式
     * @param false    $useIncludePath 是否在include目录中寻找该文件
     * @param resource $context        上下文
     */
    public function __construct($filename, $mode = 'r', $useIncludePath = false, $context = null)
    {
        if (strstr($filename, '://') === false || substr($filename, 0, 4) == 'file') {
            $autoBuild = false;
            $wchars = ['w', 'a', 'x', '+'];
            foreach ($wchars as $char) {
                if (strpos($mode, $char) !== false) {
                    $autoBuild = true;
                    break;
                }
            }
            if ($autoBuild) {
                $dir = dirname($filename);
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }
            }
            $this->path = self::realpath($filename, false);
        } else {
            $this->path = $filename;
        }
        parent::__construct($filename, $mode, $useIncludePath, $context);
    }

    /**
     * 改变当前文件所属的组
     *
     * 该函数不能在 Windows 系统上运行
     * 只有超级用户可以任意修改文件的组，其它用户可能只能将文件的组改成该用户自己所在的组。
     * @param mixed $group 组的名称或数字。
     * @return bool
     */
    public function chgrp($group): bool
    {
        if ($this->isLink()) {
            return lchgrp($this->path, $group);
        } else {
            return chgrp($this->path, $group);
        }
    }

    /**
     * 改变当前文件模式
     *
     * 参数 `$mode` :
     *   注意 mode 不会被自动当成八进制数值，而且也不能用字符串（例如 "g+w"）。
     *   要确保正确操作，需要给 mode 前面加上 0
     * @param int $mode 模式
     * @return bool
     */
    public function chmod(int $mode): bool
    {
        return chmod($this->path, $mode);
    }

    /**
     * 改变当前文件的所有者
     *
     * 该函数不能在 Windows 系统上运行
     * @param mixed $user 用户名或数字。
     * @return bool
     */
    public function chown($user): bool
    {
        if ($this->isLink()) {
            return lchown($this->path, $user);
        } else {
            return chown($this->path, $user);
        }
    }

    /**
     * 清除当前文件状态缓存
     */
    public function clearstatcache()
    {
        clearstatcache(true, $this->path);
    }

    /**
     * 将当前文件拷贝到路径dest
     * @param string      $dir   指定要复制的文件夹路径
     * @param string|null $name  指定文件名，不指定则为原文件名
     * @param bool        $cover 如果指定文件存在，是否覆盖
     * @return bool
     */
    public function copy(string $dir, string $name = null, bool $cover = false): bool
    {
        if (is_null($name)) {
            $name = $this->getBasename();
        }
        $dest = $dir . DIRECTORY_SEPARATOR . $name;
        if (!$cover && is_file($dest)) {  // 文件已存在，且不允许覆盖
            return false;
        }
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        return copy($this->path, $dest);
    }

    /**
     * 删除文件
     * @param resource $context 上下文
     * @return bool
     */
    public function delete($context = null): bool
    {
        return $this->unlink($context);
    }

    /**
     * 将整个文件读入一个字符串
     *
     * 参数 `$offset` :
     *   默认为0表示最开始地方
     * 参数 `$maxlen` :
     *   超过该长度则不读取，默认不指定全部读取
     * @param bool     $use_include_path 是否在 include_path 中搜寻文件
     * @param resource $context          上下文支持
     * @param int      $offset           插入位置偏移量
     * @param int|null $maxlen           指定读取长度
     * @return string
     */
    public function getContents(bool $use_include_path = false, $context = null, int $offset = 0, int $maxlen = null): string
    {
        if (is_null($maxlen)) {
            $contents = file_get_contents($this->path, $use_include_path, $context, $offset);
        } else {
            $contents = file_get_contents($this->path, $use_include_path, $context, $offset, $maxlen);
        }
        if ($contents === false) {
            throw new RuntimeException('error on file_get_contents');
        }
        return $contents;
    }

    /**
     * 将一个字符串写入文件
     *
     * 参数 `$data` :
     *   类型可以是 string ， array 或者是 stream 资源
     * 参数  `$flags` :
     *   可选值：[FILE_USE_INCLUDE_PATH|FILE_APPEND|LOCK_EX]
     * @param mixed    $data    要写入的数据
     * @param int      $flags   指定配置
     * @param resource $context 上下文支持
     * @return int
     */
    public function putContents($data, int $flags = 0, $context = null): int
    {
        $number = file_put_contents($this->path, $data, $flags, $context);
        if ($number === false) {
            throw new RuntimeException('error on file_put_contents');
        }
        return $number;
    }

    /**
     * 检查是否模式匹配文件名
     *
     * 普通用户可能习惯于 shell 模式或者至少其中最简单的形式 '?' 和 '*' 通配符，
     * 因此使用 fnmatch() 来代替 Preg::match() 来进行前端搜索表达式输入对于非程序员用户更加方便。
     * 参数 `$flags` :
     *   可选值：[FNM_NOESCAPE|FNM_PATHNAME|FNM_PERIOD|FNM_CASEFOLD]
     * @param string $pattern 统配符[shell]
     * @param int    $flags   指定配置
     * @return bool
     */
    public function nmatch(string $pattern, int $flags = 0): bool
    {
        return fnmatch($pattern, $this->getBasename(), $flags);
    }

    /**
     * 判断当前文件是否是通过 HTTP POST 上传的
     * @return bool
     */
    public function isUploadedFile(): bool
    {
        return is_uploaded_file($this->path);
    }

    /**
     * 建立一个硬连接
     * @param string $link 链接的名称
     * @return bool
     */
    public function link(string $link): bool
    {
        return link($this->path, $link);
    }

    /**
     * 获取一个连接的信息
     * @return int
     */
    public function linkinfo(): int
    {
        $linkinfo = linkinfo($this->path);
        if ($linkinfo === false) {
            throw new RuntimeException('error on linkinfo');
        }
        return $linkinfo;
    }

    /**
     * 读取文件并写入到输出缓冲。
     * @param bool     $use_include_path 是否在 include_path 中搜寻文件
     * @param resource $context          上下文支持
     * @return int
     */
    public function readfile(bool $use_include_path = false, $context = null): int
    {
        $number = readfile($this->path, $use_include_path, $context);
        if ($number === false) {
            throw new RuntimeException('error on readfile');
        }
        return $number;
    }

    /**
     * 返回符号连接指向的目标
     * @return string
     */
    public function readlink(): string
    {
        $contents = readlink($this->path);
        if ($contents === false) {
            throw new RuntimeException('error on readlink');
        }
        return $contents;
    }

    /**
     * 重命名
     * @param string $newname    要移动到的目标位置路径
     * @param bool   $auto_build 如果指定的路径不存在，是否创建
     * @return bool
     */
    public function rename(string $newname, bool $auto_build = true): bool
    {
        if ($auto_build) {
            $dir = dirname($newname);
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
        }
        return rename($this->path, $newname);
    }

    /**
     * 建立一个名为 link 的符号连接。
     *
     * 在Windows下该方法需要超级管理员权限
     * @param string $link 链接的名称
     * @return bool
     */
    public function symlink(string $link): bool
    {
        return symlink($this->path, $link);
    }

    /**
     * 设定文件的访问和修改时间
     *
     * 注意，如果文件不存在则尝试创建
     * @param int|null $time  要设定的修改时间
     * @param int|null $atime 要设定的访问时间
     * @return bool
     */
    public function touch(int $time = null, int $atime = null): bool
    {
        if (is_null($time)) {
            $time = time();
        }
        return touch($this->path, $time, $atime);
    }

    /**
     * 删除文件
     * @param resource $context 上下文
     * @return bool
     */
    public function unlink($context = null): bool
    {
        return unlink($this->path, $context);
    }

    /**
     * 返回文件MIME
     * @return string
     */
    public function getMime(): string
    {
        if (extension_loaded('fileinfo')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimetype = finfo_file($finfo, $this->path);
            finfo_close($finfo);
            return $mimetype;
        } elseif (function_exists('mime_content_type')) {
            return mime_content_type($this->path);
        } else {
            return 'application/octet-stream';
        }
    }

    /**
     * 尽可能的返回文件后缀名
     *
     * 对于已有后缀名的文件则直接返回，无后缀名文件通过MIME猜测其后缀名，并不能保证准确性！
     * @return string 无后缀名返回空字符串
     */
    public function getExtension(): string
    {
        $ext = parent::getExtension();
        if (!empty($ext)) {
            return $ext;
        }
        $ext = MIME::getExtensionByMime($this->getMime(), true);
        if (empty($ext)) {
            $ext = '';
        }
        return $ext;
    }

    /**
     * 检查文件是否存在
     * @param string $path 路径
     * @return bool
     */
    public static function exists(string $path): bool
    {
        $pathinfo = pathinfo($path);
        if (!is_dir($pathinfo['dirname'])) {
            return false;
        }

        if (file_exists($path)) {
            if (strstr(PHP_OS, 'WIN')) {  // Windows下严格遵守大小写
                if (dirname(realpath($path)) != Directory::realpath(dirname($path), false)) {
                    return false;
                }
                if (basename(realpath($path)) != $pathinfo['basename']) {
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
                throw new RuntimeException('path is not exists: ' . $path);
            }
            $realpath = realpath($path);
            if ($realpath === false) {
                throw new RuntimeException('path cannot realpath');
            }
            return $realpath;
        } else {
            if (self::exists($path)) {
                $realpath = realpath($path);
                if ($realpath === false) {
                    throw new RuntimeException('path cannot realpath');
                }
                return $realpath;
            }
            return Directory::realpath(dirname($path), false) . DIRECTORY_SEPARATOR . basename($path);
        }
    }
}
