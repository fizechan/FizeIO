<?php


namespace fize\io;

use SplFileObject;

/**
 * 文件操作类
 * @todo 未处理函数：parse_ini_file、parse_ini_string
 */
class File
{

    /**
     * @var string 当前文件完整路径
     */
    private $path;

    /**
     * @var resource 当前文件句柄
     */
    private $resource;

    /**
     * @var bool 是否为单向通道
     */
    private $progress = false;

    /**
     * @var string 打开模式
     */
    private $mode;

    /**
     * 构造
     *
     * 参数 `$filename` :
     *   对于 popen 可以使用null来指定
     *   可以传入上下文流进行流操作
     * @param string $filename 文件名
     * @param string $mode 打开模式
     */
    public function __construct($filename = null, $mode = null)
    {
        if(is_resource($filename)) {
            $this->resource = $filename;
        } else {
            $this->path = $filename;
        }
        $this->mode = $mode;
        $auto_build = in_array($mode, ['r+', 'w', 'w+', 'a', 'a+', 'x', 'x+']);
        if ($filename && $auto_build) {
            $dir = dirname($this->path);
            Directory::createDirectory($dir, 0777, true);
            touch($filename);
        }
    }

    /**
     * 析构
     *
     * 清理资源对象，防止内存泄漏
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * 返回对应的SPL文件对象
     * @return SplFileObject
     */
    public function getSplFileObject()
    {
        return new SplFileObject($this->path, $this->mode);
    }

    /**
     * 获取资源流
     * @return resource
     */
    public function getStream()
    {
        return $this->resource;
    }

    /**
     * 返回路径中的文件名部分
     * @param string $suffix 如果文件名是以 suffix 结束的，那这一部分也会被去掉
     * @return string
     */
    public function basename($suffix = null)
    {
        return basename($this->path, $suffix);
    }

    /**
     * 改变当前文件所属的组
     *
     * 该函数不能在 Windows 系统上运行
     * 只有超级用户可以任意修改文件的组，其它用户可能只能将文件的组改成该用户自己所在的组。
     * @param mixed $group 组的名称或数字。
     * @return bool
     */
    public function chgrp($group)
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
    public function chmod($mode)
    {
        $this->mode = $mode;
        return chmod($this->path, $mode);
    }

    /**
     * 改变当前文件的所有者
     *
     * 该函数不能在 Windows 系统上运行
     * @param mixed $user 用户名或数字。
     * @return bool
     */
    public function chown($user)
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
     * @param string $dir 指定要复制的文件夹路径
     * @param string $name 指定文件名，不指定则为原文件名
     * @param bool $cover 如果指定文件存在，是否覆盖
     * @return bool
     */
    public function copy($dir, $name = null, $cover = false)
    {
        if (is_null($name)) {
            $name = $this->basename();
        }
        $dest = $dir . "/" . $name;
        if (!$cover && is_file($dest)) {  //文件已存在，且不允许覆盖
            return false;
        }
        Directory::createDirectory($dir, 0777, true);
        return copy($this->path, $dest);
    }

    /**
     * 删除当前文件
     * @param resource $context 上下文
     * @return bool 没有该文件也返回true
     */
    public function delete($context = null)
    {
        if (is_file($this->path)) {
            return unlink($this->path, $context);
        } else {
            return true;
        }
    }

    /**
     * 返回当前文件路径中的目录部分
     * @return string
     */
    public function dirname()
    {
        return dirname($this->path);
    }

    /**
     * 关闭当前文件
     * @return bool
     */
    public function close()
    {
        $result = false;
        if($this->resource) {
            if ($this->progress) {
                $result = pclose($this->resource);
            } else {
                $result = fclose($this->resource);
            }
        }

        if ($result) {
            $this->resource = null; //如果正确关闭了则清空当前对象的file_resource
        }

        return $result;
    }

    /**
     * 测试文件指针是否到了文件结束的位置
     * @return bool
     */
    public function eof()
    {
        return feof($this->resource);
    }

    /**
     * 将缓冲内容输出到文件
     * @return bool
     */
    public function flush()
    {
        return fflush($this->resource);
    }

    /**
     * 从文件指针中读取一个字符。 碰到 EOF 则返回 FALSE 。
     * @return string 如果碰到 EOF 则返回 FALSE。
     */
    public function getc()
    {
        return fgetc($this->resource);
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
     * @param int $length 规定行的最大长度
     * @param string $delimiter 设置字段分界符
     * @param string $enclosure 设置字段环绕符
     * @param string $escape 设置转义字符
     * @return array 如果碰到 EOF 则返回 FALSE。
     */
    public function getcsv($length = 0, $delimiter = ",", $enclosure = '"', $escape = "\\")
    {
        return fgetcsv($this->resource, $length, $delimiter, $enclosure, $escape);
    }

    /**
     * 从文件指针中读取一行
     *
     * 参数 `$length` :
     *   默认是 1024 字节。
     *   实际返回的字节是 $length - 1
     * @param int $length 规定要读取的字节数
     * @return string 若失败，则返回 false。
     */
    public function gets($length = null)
    {
        if (is_null($length)) {
            $rst = fgets($this->resource);
        } else {
            $rst = fgets($this->resource, $length);
        }
        return $rst;
    }

    /**
     * 从文件指针中读取一行并过滤掉HTML和PHP标记。
     *
     * 参数 `$length` :
     *   默认是 1024 字节
     * 参数 `$allowable_tags` :
     *   形如“<p>,<b>”
     * @param int $length 规定要读取的字节数
     * @param string $allowable_tags 规定不会被删除的标签
     * @return string
     * @deprecated PHP7.3不建议使用该方法
     */
    public function getss($length = null, $allowable_tags = null)
    {
        if (is_null($length)) {
            $rst = fgetss($this->resource);
        } else {
            $rst = fgetss($this->resource, $length, $allowable_tags);
        }
        return $rst;
    }

    /**
     * 检查文件是否存在
     * @param string $path 路径
     * @return bool
     */
    public static function exists($path)
    {
        return file_exists($path);
    }

    /**
     * 将整个文件读入一个字符串
     *
     * 参数 `$offset` :
     *   默认为0表示最开始地方
     * 参数 `$maxlen` :
     *   超过该长度则不读取，默认不指定全部读取
     * @param bool $use_include_path 是否在 include_path 中搜寻文件
     * @param resource $context 上下文支持
     * @param int $offset 插入位置偏移量
     * @param int $maxlen 指定读取长度
     * @return string
     */
    public function getContents($use_include_path = false, $context = null, $offset = 0, $maxlen = null)
    {
        if (is_null($maxlen)) {
            return file_get_contents($this->path,$use_include_path, $context, $offset, $maxlen);
        } else {
            return file_get_contents($this->path, $use_include_path, $context, $offset, $maxlen);
        }
    }

    /**
     * 将一个字符串写入文件
     *
     * 参数 `$data` :
     *   类型可以是 string ， array 或者是 stream 资源
     * 参数  `$flags` :
     *   可选值：[FILE_USE_INCLUDE_PATH|FILE_APPEND|LOCK_EX]
     * @param mixed $data 要写入的数据
     * @param int $flags 指定配置
     * @param resource $context 上下文支持
     * @return int
     */
    public function putContents($data, $flags = 0, $context = null)
    {
        return file_put_contents($this->path, $data, $flags, $context);
    }

    /**
     * 把整个文件读入一个数组中
     *
     * 参数 `$flags` :
     *   可选值：[FILE_USE_INCLUDE_PATH|FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES]
     * @param int $flags 指定配置
     * @return array
     */
    public function file($flags = 0)
    {
        return file($this->path, $flags);
    }

    /**
     * 取得文件的上次访问时间
     * @return int
     */
    public function atime()
    {
        return fileatime($this->path);
    }

    /**
     * 取得文件的 inode 修改时间
     * @return int
     */
    public function ctime()
    {
        return filectime($this->path);
    }

    /**
     * 取得文件的组
     * @return int
     */
    public function group()
    {
        return filegroup($this->path);
    }

    /**
     * 文件的inode
     * @return int
     */
    public function inode()
    {
        return fileinode($this->path);
    }

    /**
     * 文件修改时间
     * @return int
     */
    public function mtime()
    {
        return filemtime($this->path);
    }

    /**
     * 文件的所有者
     * @return int
     */
    public function owner()
    {
        return fileowner($this->path);
    }

    /**
     * 文件的权限
     * @return int
     */
    public function perms()
    {
        return fileperms($this->path);
    }

    /**
     * 文件大小(字节数)
     * @return int
     */
    public function size()
    {
        return filesize($this->path);
    }

    /**
     * 文件类型
     *
     * (可能的值有 fifo，char，dir，block，link，file 和 unknown。)
     * @return string
     */
    public function type()
    {
        return filetype($this->path);
    }

    /**
     * 轻便的咨询文件锁定
     *
     * 参数 `$operation` :
     *   可选值：[LOCK_SH|LOCK_EX|LOCK_UN]
     * @param int $operation 操作
     * @param int $wouldblock 如果锁定会堵塞的话返回1
     * @return bool
     */
    public function lock($operation, &$wouldblock = null)
    {
        return flock($this->resource, $operation, $wouldblock);
    }

    /**
     * 检查是否模式匹配文件名
     *
     * 普通用户可能习惯于 shell 模式或者至少其中最简单的形式 '?' 和 '*' 通配符，
     * 因此使用 fnmatch() 来代替 Preg::match() 来进行前端搜索表达式输入对于非程序员用户更加方便。
     * 参数 `$flags` :
     *   可选值：[FNM_NOESCAPE|FNM_PATHNAME|FNM_PERIOD|FNM_CASEFOLD]
     * @param string $pattern 统配符[shell]
     * @param int $flags 指定配置
     * @return bool
     */
    public function nmatch($pattern, $flags = 0)
    {
        return fnmatch($pattern, $this->basename(), $flags);
    }

    /**
     * 打开文件或者 URL
     * @param string 打开模式，不指定则为当前模式
     * @param bool $use_include_path 是否在 include_path 中搜寻文件
     * @param resource $context 上下文支持
     */
    public function open($mode = null, $use_include_path = false, $context = null)
    {
        $this->progress = false;
        $mode = $mode ? $mode : $this->mode;
        $this->resource = fopen($this->path, $mode, $use_include_path, $context);
    }

    /**
     * 输出文件指针处的所有剩余数据
     * @return int 返回剩余数据字节数
     */
    public function passthru()
    {
        return fpassthru($this->resource);
    }

    /**
     * 将行格式化为 CSV 并写入文件指针
     * @param array $fields 要写入的数组数据
     * @param string $delimiter 分隔符
     * @param string $enclosure 界限符
     * @param string $escape_char 转义符
     * @return int 如果失败返回false
     */
    public function putcsv(array $fields, $delimiter = ",", $enclosure = '"', $escape_char = "\\")
    {
        return fputcsv($this->resource, $fields, $delimiter, $enclosure, $escape_char);
    }

    /**
     * 写入文件（可安全用于二进制文件）
     * @param string $string 要写入的字符串
     * @param int $length 指定写入长度
     * @return int 如果失败返回false
     */
    public function puts($string, $length = null)
    {
        if (is_null($length)) {
            $rst = fputs($this->resource, $string);
        } else {
            $rst = fputs($this->resource, $string, $length);
        }
        return $rst;
    }

    /**
     * 读取文件（可安全用于二进制文件）
     * @param int $length
     * @return string
     */
    public function read($length)
    {
        return fread($this->resource, $length);
    }

    /**
     * 从文件中格式化输入
     * @param string $format
     * @return array
     */
    public function scanf($format)
    {
        return fscanf($this->resource, $format);
    }

    /**
     * 在文件指针中定位
     *
     * 参数 `$offset` :
     *   要移动到文件尾之前的位置，需要给 offset 传递一个负值，并设置 whence 为 SEEK_END。
     * 参数 `$whence` :
     *   SEEK_SET - 设定位置等于 offset 字节。
     *   SEEK_CUR - 设定位置为当前位置加上 offset。
     *   SEEK_END - 设定位置为文件尾加上 offset。
     * @param int $offset 偏移量
     * @param int $whence 设置方式
     * @return int
     */
    public function seek($offset, $whence = 0)
    {
        return fseek($this->resource, $offset, $whence);
    }

    /**
     * 通过已打开的文件指针取得文件信息
     * @return array
     */
    public function stat()
    {
        if ($this->isLink()) {
            return lstat($this->path);
        } else {
            if ($this->resource) {
                return fstat($this->resource);
            } else {
                return stat($this->path);
            }
        }
    }

    /**
     * 返回文件指针读/写的位置
     * @return int
     */
    public function tell()
    {
        return ftell($this->resource);
    }

    /**
     * 将文件截断到给定的长度
     * @param int $size 指定长度
     * @return bool
     */
    public function truncate($size)
    {
        return ftruncate($this->resource, $size);
    }

    /**
     * 写入文件（可安全用于二进制文件）
     * @param string $string 要写入的字符串
     * @param int $length 指定写入长度
     * @return int 失败时返回false
     */
    public function write($string, $length = null)
    {
        if (is_null($length)) {
            $rst = fwrite($this->resource, $string);
        } else {
            $rst = fwrite($this->resource, $string, $length);
        }
        return $rst;
    }

    /**
     * 文件是否可执行
     * @return bool
     */
    public function isExecutable()
    {
        return is_executable($this->path);
    }

    /**
     * 判断是否为一个正常的文件
     * @return bool
     */
    public function isFile()
    {
        return is_file($this->path);
    }

    /**
     * 判断是否为符号连接
     * @return bool
     */
    public function isLink()
    {
        return is_link($this->path);
    }

    /**
     * 判断是否可读
     * @return bool
     */
    public function isReadable()
    {
        return is_readable($this->path);
    }

    /**
     * 判断当前文件是否是通过 HTTP POST 上传的
     * @return bool
     */
    public function isUploadedFile()
    {
        return is_uploaded_file($this->path);
    }

    /**
     * 判断当前文件是否可写
     * @return bool
     */
    public function isWritable()
    {
        return is_writable($this->path);
    }

    /**
     * 建立一个硬连接
     * @param string $link 链接的名称
     * @return bool
     */
    public function link($link)
    {
        return link($this->path, $link);
    }

    /**
     * 获取一个连接的信息
     * @return int
     */
    public function linkinfo()
    {
        return linkinfo($this->path);
    }

    /**
     * 返回文件路径的信息
     *
     * 参数 `$options` :
     *   如果没有传入 options ，将会返回包括以下单元的数组 array ：dirname，basename和 extension（如果有），以 及filename。
     * @param mixed $options 选项
     * @return mixed
     */
    public function pathinfo($options = null)
    {
        if (is_null($options)) {
            return pathinfo($this->path);
        } else {
            return pathinfo($this->path, $options);
        }
    }

    /**
     * 打开一个指向进程的管道
     * @param string $command 命令
     * @param string 模式
     */
    public function popen($command, $mode = null)
    {
        $this->progress = true;
        $mode = $mode ? $mode : $this->mode;
        $this->resource = popen($command, $mode);
    }

    /**
     * 读取文件并写入到输出缓冲。
     * @param bool $use_include_path 是否在 include_path 中搜寻文件
     * @param resource $context 上下文支持
     * @return int
     */
    public function readfile($use_include_path = false, $context = null)
    {
        return readfile($this->path, $use_include_path, $context);
    }

    /**
     * 返回符号连接指向的目标
     * @return string
     */
    public function readlink()
    {
        return readlink($this->path);
    }

    /**
     * 获取真实目录缓存的详情
     * @return array
     */
    public static function realpathCacheGet()
    {
        return realpath_cache_get();
    }

    /**
     * 获取真实路径缓冲区的大小
     * @return int
     */
    public static function realpathCacheSize()
    {
        return realpath_cache_size();
    }

    /**
     * 返回规范化的绝对路径名
     * @return string
     */
    public function realpath()
    {
        return realpath($this->path);
    }

    /**
     * 重命名一个文件,可用于移动文件
     * @param string $newname 要移动到的目标位置路径
     * @param bool $auto_build 如果指定的路径不存在，是否创建
     * @return bool
     */
    public function rename($newname, $auto_build = true)
    {
        if ($auto_build) {
            $dir = dirname($newname);
            Directory::createDirectory($dir, 0777, true);
        }
        return rename($this->path, $newname);
    }

    /**
     * 倒回文件指针的位置
     * @return bool
     */
    public function rewind()
    {
        return rewind($this->resource);
    }

    /**
     * 设置当前打开文件的缓冲大小。
     * @param int $buffer 规定缓冲大小，以字节计。
     * @return mixed 未启动句柄时返回false；否则如果成功，该函数返回 0，否则返回 EOF。
     */
    public function setBuffer($buffer)
    {
        return set_file_buffer($this->resource, $buffer);
    }

    /**
     * 建立一个名为 link 的符号连接。
     *
     * 在Windows下该方法需要超级管理员权限
     * @param string $link 链接的名称
     * @return bool
     */
    public function symlink($link)
    {
        return symlink($this->path, $link);
    }

    /**
     * 建立一个临时文件
     * @return resource
     */
    public static function tmpfile()
    {
        return tmpfile();
    }

    /**
     * 设定文件的访问和修改时间
     *
     * 注意，如果文件不存在则尝试创建
     * @param int $time 要设定的修改时间
     * @param int $atime 要设定的访问时间
     * @return bool
     */
    public function touch($time = null, $atime = null)
    {
        if (is_null($time)) {
            $time = time();
        }
        return touch($this->path, $time, $atime);
    }

    /**
     * 获取或改变当前的umask
     * @param int $mask 指定该值时将改变当前umask
     * @return int
     */
    public static function umask($mask = null)
    {
        if (is_null($mask)) {
            return umask();
        } else {
            return umask($mask);
        }
    }

    /**
     * 删除文件
     * @param resource $context 上下文
     * @return bool
     */
    public function unlink($context = null)
    {
        return unlink($this->path, $context);
    }
}
