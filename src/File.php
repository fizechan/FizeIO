<?php


namespace fize\io;

use SplFileObject;

/**
 * 文件操作类
 * @package fize\io
 */
class File extends SplFileObject
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
     * @param string $filename 完整含目录文件名
     * @param string $mode 打开模式，默认r
     */
    public function __construct($filename, $mode = 'r')
    {
        $this->path = $filename;
        $this->mode = $mode;
        $auto_build = in_array($mode, ['r+', 'w', 'w+', 'a', 'a+', 'x', 'x+']);
        if ($auto_build) {
            $dir = dirname($this->path);
            Directory::createDirectory($dir, 0777, true);
            touch($filename);
        }
        parent::__construct($filename, $mode);
    }

    /**
     * 改变当前文件所属的组
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
     * @param int $mode 注意 mode 不会被自动当成八进制数值，而且也不能用字符串（例如 "g+w"）。要确保正确操作，需要给 mode 前面加上 0
     * @return bool
     */
    public function chmod($mode)
    {
        $this->mode = $mode;
        return chmod($this->path, $mode);
    }

    /**
     * 改变当前文件的所有者
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
     * @param string $dest 指定路径
     * @param string $name 指定文件名，不指定则为原文件名
     * @param bool $cover 如果指定文件存在，是否覆盖
     * @return bool
     */
    public function copy($dest, $name = null, $cover = false)
    {
        if (is_null($name)) {
            $name = $this->getBaseName();
        }
        $full_dest = $dest . "/" . $name;
        if (!$cover && is_file($full_dest)) {  //文件已存在，且不允许覆盖
            return false;
        }
        Directory::createDirectory($dest, 0777, true);
        return copy($this->path, $full_dest);
    }

    /**
     * 删除当前文件
     * @return bool 没有该文件也返回true
     */
    public function delete()
    {
        if (is_file($this->path)) {
            return unlink($this->path);
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
        if ($this->progress) {
            $result = pclose($this->resource);
        } else {
            $result = fclose($this->resource);
        }

        if ($result) {
            $this->resource = null; //如果正确关闭了则清空当前对象的file_resource
        }

        return $result;
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
     * @param int $length 规定行的最大长度。必须大于 CVS 文件内最长的一行。
     * @param string $delimiter 设置字段分界符（只允许一个字符），默认值为逗号。
     * @param string $enclosure 设置字段环绕符（只允许一个字符），默认值为双引号。
     * @param string $escape 设置转义字符（只允许一个字符），默认是一个反斜杠。
     * @return array 如果碰到 EOF 则返回 FALSE。
     */
    public function getcsv($length = 0, $delimiter = ",", $enclosure = '"', $escape = "\\")
    {
        return fgetcsv($this->resource, $length, $delimiter, $enclosure, $escape);
    }

    /**
     * 从文件指针中读取一行
     * @param int $length 规定要读取的字节数。默认是 1024 字节。
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
     * @param int $length 规定要读取的字节数。默认是 1024 字节
     * @param string $allowable_tags 规定不会被删除的标签。形如“<p>,<b>”
     * @return string
     * @deprecated 不建议使用该方法
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
     * @param int $offset 插入位置偏移量，默认为0表示最开始地方
     * @param int $maxlen 指定读取长度，超过该长度则不读取，默认不指定全部读取
     * @return string
     */
    public function getContents($offset = 0, $maxlen = null)
    {
        if (is_null($maxlen)) {
            return file_get_contents($this->path, false, null, $offset);
        } else {
            return file_get_contents($this->path, false, null, $offset, $maxlen);
        }
    }

    /**
     * 将一个字符串写入文件
     * @param mixed $data 要写入的数据。类型可以是 string ， array 或者是 stream 资源
     * @param int $flags [FILE_USE_INCLUDE_PATH|FILE_APPEND|LOCK_EX] 指定配置
     * @return int
     */
    public function putContents($data, $flags = 0)
    {
        return file_put_contents($this->path, $data, $flags);
    }

    /**
     * 把整个文件读入一个数组中
     * @param int $flags [FILE_USE_INCLUDE_PATH|FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES] 指定配置
     * @return array
     */
    public function getContentsOnArray($flags = 0)
    {
        return file($this->path, $flags);
    }

    /**
     * 获取文件信息
     * @param string $key 信息名
     * @return mixed
     */
    public function getInfo($key)
    {
        switch ($key) {
            case 'atime' : //上次访问时间
                $rst = fileatime($this->path);
                break;
            case 'ctime' : //inode修改时间
                $rst = filectime($this->path);
                break;
            case 'group' : //文件的组
                $rst = filegroup($this->path);
                break;
            case 'inode' : //文件的inode
                $rst = fileinode($this->path);
                break;
            case 'mtime' : //文件修改时间
                $rst = filemtime($this->path);
                break;
            case 'owner' : //文件的所有者
                $rst = fileowner($this->path);
                break;
            case 'perms' : //文件的权限
                $rst = fileperms($this->path);
                break;
            case 'size' : //文件大小
                $rst = filesize($this->path);
                break;
            case 'type' : //文件类型
                $rst = filetype($this->path);
                break;
            default :
                $rst = false;
        }
        return $rst;
    }

    /**
     * 轻便的咨询文件锁定
     * @param int $operation [LOCK_SH|LOCK_EX|LOCK_UN]
     * @param int $wouldblock 如果锁定会堵塞的话返回1
     * @return bool
     */
    public function lock($operation, &$wouldblock = null)
    {
        return flock($this->resource, $operation, $wouldblock);
    }

    /**
     * 用模式匹配文件名
     * @param string $pattern shell 统配符
     * @param int $flags [FNM_NOESCAPE|FNM_PATHNAME|FNM_PERIOD|FNM_CASEFOLD] 指定配置
     * @return bool
     */
    public function nmatch($pattern, $flags = 0)
    {
        return fnmatch($pattern, $this->getBaseName(), $flags);
    }

    /**
     * 打开当前文件用于读取和写入
     * @param string $mode 访问模式,未指定则为当前模式
     * @param bool $progress 指向进程文件
     * @param string $command 命令
     * @return resource
     * @todo 针对popen执行进程文件还存在问题，待修复
     */
    public function open($mode = null, $progress = false, $command = '')
    {
        if(is_null($mode)) {
            $mode = $this->mode;
        }
        $this->progress = $progress;
        if ($progress) {
            $res = popen($command, $mode);
            var_dump($res);
        } else {
            if (is_file($this->path)) {
                $res = fopen($this->path, $mode);
            } else {
                $res = false;
            }
        }
        $this->resource = $res;
        return $res;
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
    public function isWriteable()
    {
        return is_writeable($this->path);
    }

    /**
     * 建立一个硬连接(不能运行在windows环境下)
     * @param string $target 要链接的目标
     * @return bool
     */
    public function link($target)
    {
        return link($target, $this->path);
    }

    /**
     * 获取一个连接的信息(不能运行在windows环境下)
     * @return int
     */
    public function linkinfo()
    {
        return linkinfo($this->path);
    }

    /**
     * 返回文件路径的信息
     * @param mixed $options 如果没有传入 options ，将会返回包括以下单元的数组 array ：dirname，basename和 extension（如果有），以 及filename。
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
     * 读取文件并写入到输出缓冲。
     * @return int
     */
    public function readfile()
    {
        return readfile($this->path);
    }

    /**
     * 返回符号连接指向的目标(不能运行在windows环境下)
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
     * 重命名一个文件,可用于移动文件
     * @param string $newname 要移动到的目标位置路径
     * @param bool $auto_build 如果指定的路径不存在，是否创建，默认true
     * @return bool
     * @todo 测试时发现问题
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
     * 对于已有的 target 建立一个名为 link 的符号连接。(不能运行在windows环境下)
     * @param string $target 目标路径
     * @return bool
     */
    public function symlink($target)
    {
        return symlink($target, $this->path);
    }

    /**
     * 设定文件的访问和修改时间
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
     * 设置当前打开文件的缓冲大小。
     * @param int $buffer 规定缓冲大小，以字节计。
     * @return mixed 为启动句柄时返回false；否则如果成功，该函数返回 0，否则返回 EOF。
     */
    public function setBuffer($buffer)
    {
        return set_file_buffer($this->resource, $buffer);
    }

    /**
     * 建立一个临时文件
     * @return resource
     */
    public static function tmpfile()
    {
        return tmpfile();
    }
}
