<?php


namespace fize\io;

use SplFileObject;

/**
 * 文件操作类
 * @todo 未处理函数:glob,realpath_cache_get,realpath_cache_size,tmpfile.
 */
class File extends SplFileObject
{

    /**
     * 当前文件完整路径
     * @var string
     */
    private $_path;

    /**
     * 当前文件句柄
     * @var resource
     */
    private $_resource;

    /**
     * 构造
     * @param string $filename 完整含目录文件名
     * @param string $mode 打开模式，默认r
     */
    public function __construct($filename, $mode = 'r')
    {
        $this->_path = $filename;
        $auto_build = in_array($mode, ['r+', 'w', 'w+', 'a', 'a+', 'x', 'x+']);
        if ($auto_build) {
            $dir = dirname($this->_path);
            new Directory($dir, true, false);
            touch($filename);
        }
        parent::__construct($filename, $mode);
        $this->open($mode);
    }

    /**
     * 改变当前文件所属的组
     * @param mixed $group 组的名称或数字。
     * @return bool
     */
    public function changeGroup($group)
    {
        if ($this->isLink()) {
            return lchgrp($this->_path, $group);
        } else {
            return chgrp($this->_path, $group);
        }
    }

    /**
     * 改变当前文件模式
     * @param int $mode 注意 mode 不会被自动当成八进制数值，而且也不能用字符串（例如 "g+w"）。要确保正确操作，需要给 mode 前面加上 0：
     * @return bool
     */
    public function changeMode($mode)
    {
        return chmod($this->_path, $mode);
    }

    /**
     * 改变当前文件的所有者
     * @param mixed $user 用户名或数字。
     * @return bool
     */
    public function changeOwner($user)
    {
        if ($this->isLink()) {
            return lchown($this->_path, $user);
        } else {
            return chown($this->_path, $user);
        }
    }

    /**
     * 清除当前文件状态缓存
     */
    public function clearStatCache()
    {
        clearstatcache(true, $this->_path);
    }

    /**
     * 将当前文件拷贝到路径dest
     * @param string $dest 指定路径
     * @param string $name 指定文件名，不指定则为原文件名
     * @param bool $cover 如果指定文件存在，是否覆盖
     * @return bool
     */
    public function copyTo($dest, $name = "", $cover = false)
    {
        if (empty($name)) {
            $name = $this->getBaseName();
        }
        $full_dest = $dest . "/" . $name;
        if (!$cover && is_file($full_dest)) {
            return false; //文件已存在，且不允许覆盖
        }
        new Directory($dest, true, false);
        return copy($this->_path, $full_dest);
    }

    /**
     * 删除当前文件
     * @return bool
     */
    public function delete()
    {
        if (is_file($this->_path)) {
            return unlink($this->_path);
        } else {
            return true; //没有该文件则返回true
        }
    }

    /**
     * 返回当前文件路径中的目录部分
     * @return string
     */
    public function getDirName()
    {
        return dirname($this->_path);
    }

    /**
     * 关闭当前文件
     * @param bool $progress
     * @return bool
     */
    public function close($progress = false)
    {
        if ($this->_resource) {
            if ($progress) {
                $result = pclose($this->_resource);
            } else {
                $result = fclose($this->_resource);
            }
        } else {
            $result = true; //已关闭则返回true
        }

        if ($result) {
            $this->_resource = null; //如果正确关闭了则清空当前对象的file_resource
        }

        return $result;
    }

    /**
     * 将缓冲内容输出到文件
     * @return bool
     */
    public function flush()
    {
        if ($this->_resource) {
            return fflush($this->_resource);
        } else {
            return false;
        }
    }

    /**
     * 从文件指针中读取一个字符。 碰到 EOF 则返回 FALSE 。
     * @return string 如果碰到 EOF 则返回 FALSE。
     */
    public function getC()
    {
        if ($this->_resource) {
            return fgetc($this->_resource);
        } else {
            return '';
        }
    }

    /**
     * 从文件指针中读入一行并解析 CSV 字段
     * @param int $length 规定行的最大长度。必须大于 CVS 文件内最长的一行。
     * @param string $delimiter 设置字段分界符（只允许一个字符），默认值为逗号。
     * @param string $enclosure 设置字段环绕符（只允许一个字符），默认值为双引号。
     * @param string $escape 设置转义字符（只允许一个字符），默认是一个反斜杠。
     * @return array 如果碰到 EOF 则返回 FALSE。
     */
    public function getCSV($length = 0, $delimiter = ",", $enclosure = '"', $escape = "\\")
    {
        if ($this->_resource) {
            return fgetcsv($this->_resource, $length, $delimiter, $enclosure, $escape);
        } else {
            return [];
        }
    }

    /**
     * 从文件指针中读取一行
     * @param int $length 规定要读取的字节数。默认是 1024 字节。
     * @return string 若失败，则返回 false。
     */
    public function getS($length = null)
    {
        if ($this->_resource) {
            if (is_null($length)) {
                $rst = fgets($this->_resource);
            } else {
                $rst = fgets($this->_resource, $length);
            }
            return $rst;
        } else {
            return '';
        }
    }

    /**
     * 从文件指针中读取一行并过滤掉HTML和PHP标记。
     * @deprecated 不建议使用该方法
     * @param int $length 规定要读取的字节数。默认是 1024 字节
     * @param string $allowable_tags 规定不会被删除的标签。形如“<p>,<b>”
     * @return string
     */
    public function getSS($length = null, $allowable_tags = null)
    {
        if ($this->_resource) {
            if (is_null($length)) {
                $rst = fgetss($this->_resource);
            } else {
                $rst = fgetss($this->_resource, $length, $allowable_tags);
            }
            return $rst;
        } else {
            return '';
        }
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
            return file_get_contents($this->_path, false, null, $offset);
        } else {
            return file_get_contents($this->_path, false, null, $offset, $maxlen);
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
        return file_put_contents($this->_path, $data, $flags);
    }

    /**
     * 把整个文件读入一个数组中
     * @param int $flags [FILE_USE_INCLUDE_PATH|FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES] 指定配置
     * @return array
     */
    public function getContentsOnArray($flags = 0)
    {
        return file($this->_path, $flags);
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
                $rst = fileatime($this->_path);
                break;
            case 'ctime' : //inode修改时间
                $rst = filectime($this->_path);
                break;
            case 'group' : //文件的组
                $rst = filegroup($this->_path);
                break;
            case 'inode' : //文件的inode
                $rst = fileinode($this->_path);
                break;
            case 'mtime' : //文件修改时间
                $rst = filemtime($this->_path);
                break;
            case 'owner' : //文件的所有者
                $rst = fileowner($this->_path);
                break;
            case 'perms' : //文件的权限
                $rst = fileperms($this->_path);
                break;
            case 'size' : //文件大小
                $rst = filesize($this->_path);
                break;
            case 'type' : //文件类型
                $rst = filetype($this->_path);
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
        if ($this->_resource) {
            return flock($this->_resource, $operation, $wouldblock);
        } else {
            return false;
        }
    }

    /**
     * 用模式匹配文件名
     * @param string $pattern shell 统配符
     * @param int $flags [FNM_NOESCAPE|FNM_PATHNAME|FNM_PERIOD|FNM_CASEFOLD] 指定配置
     * @return bool
     */
    public function nameMatch($pattern, $flags = 0)
    {
        return fnmatch($pattern, $this->getBaseName(), $flags);
    }

    /**
     * 打开当前文件用于读取和写入
     * @todo 针对popen执行进程文件还存在问题，待修复
     * @param string $mode 访问类型
     * @param bool $progress 指向进程文件
     * @param string $command 命令
     * @return resource
     */
    public function open($mode, $progress = false, $command = '')
    {
        if ($progress) {
            $res = popen($command, $mode);
            var_dump($res);
        } else {
            if (is_file($this->_path)) {
                $res = fopen($this->_path, $mode);
            } else {
                $res = false;
            }
        }
        if ($res) {
            $this->_resource = $res;
        }
        return $res;
    }

    /**
     * 输出文件指针处的所有剩余数据
     * @return int 返回剩余数据字节数
     */
    public function passthru()
    {
        if ($this->_resource) {
            return fpassthru($this->_resource);
        } else {
            return 0;
        }
    }

    /**
     * 将行格式化为 CSV 并写入文件指针
     * @param array $fields 要写入的数组数据
     * @param string $delimiter 分隔符
     * @param string $enclosure 界限符
     * @param string $escape_char 转义符
     * @return int 如果失败返回false
     */
    public function putCSV(array $fields, $delimiter = ",", $enclosure = '"', $escape_char = "\\")
    {
        if ($this->_resource) {
            return fputcsv($this->_resource, $fields, $delimiter, $enclosure, $escape_char);
        } else {
            return false;
        }
    }

    /**
     * 写入文件（可安全用于二进制文件）
     * @param string $string 要写入的字符串
     * @param int $length 指定写入长度
     * @return int 如果失败返回false
     */
    public function putS($string, $length = null)
    {
        if ($this->_resource) {
            if (is_null($length)) {
                $rst = fputs($this->_resource, $string);
            } else {
                $rst = fputs($this->_resource, $string, $length);
            }
            return $rst;
        } else {
            return false;
        }
    }

    /**
     * 读取文件（可安全用于二进制文件）
     * @param int $length
     * @return string
     */
    public function read($length)
    {
        if ($this->_resource) {
            return fread($this->_resource, $length);
        } else {
            return '';
        }
    }

    /**
     * 从文件中格式化输入
     * @param string $format
     * @return array
     */
    public function scanf($format)
    {
        if ($this->_resource) {
            return fscanf($this->_resource, $format);
        } else {
            return [];
        }
    }

    /**
     * 通过已打开的文件指针取得文件信息
     * @return array
     */
    public function getStat()
    {
        if ($this->isLink()) {
            return lstat($this->_path);
        } else {
            if ($this->_resource) {
                return fstat($this->_resource);
            } else {
                return stat($this->_path);
            }
        }
    }

    /**
     * 返回文件指针读/写的位置
     * @return int
     */
    public function tell()
    {
        if ($this->_resource) {
            return ftell($this->_resource);
        } else {
            return false;
        }
    }

    /**
     * 将文件截断到给定的长度
     * @param int $size 指定长度
     * @return bool
     */
    public function truncate($size)
    {
        if ($this->_resource) {
            return ftruncate($this->_resource, $size);
        } else {
            return false;
        }
    }

    /**
     * 写入文件（可安全用于二进制文件）
     * @param string $string 要写入的字符串
     * @param int $length 指定写入长度
     * @return int 失败时返回false
     */
    public function write($string, $length = null)
    {
        if ($this->_resource) {
            if (is_null($length)) {
                $rst = fwrite($this->_resource, $string);
            } else {
                $rst = fwrite($this->_resource, $string, $length);
            }
            return $rst;
        } else {
            return false;
        }
    }

    /**
     * 判断当前文件是否是通过 HTTP POST 上传的
     * @return bool
     */
    public function isUploadedFile()
    {
        return is_uploaded_file($this->_path);
    }

    /**
     * 判断当前文件是否可写
     * @return bool
     */
    public function isWriteable()
    {
        return is_writeable($this->_path);
    }

    /**
     * 建立一个硬连接(不能运行在windows环境下)
     * @param string $target 要链接的目标
     * @return bool
     */
    public function linkTo($target)
    {
        return link($target, $this->_path);
    }

    /**
     * 获取一个连接的信息(不能运行在windows环境下)
     * @return int
     */
    public function getLinkInfo()
    {
        return linkinfo($this->_path);
    }

    /**
     * 返回文件路径的信息
     * @param mixed $options 如果没有传入 options ，将会返回包括以下单元的数组 array ：dirname，basename和 extension（如果有），以 及filename。
     * @return mixed
     */
    public function pathInfo($options = null)
    {
        if (is_null($options)) {
            return pathinfo($this->_path);
        } else {
            return pathinfo($this->_path, $options);
        }
    }

    /**
     * 读取文件并写入到输出缓冲。
     * @return int
     */
    public function echoReadFile()
    {
        return readfile($this->_path);
    }

    /**
     * 返回符号连接指向的目标(不能运行在windows环境下)
     * @return string
     */
    public function returnReadLink()
    {
        return readlink($this->_path);
    }

    /**
     * 重命名一个文件,可用于移动文件
     * @todo 测试时发现问题
     * @param string $newname 要移动到的目标位置路径
     * @param bool $auto_build 如果指定的路径不存在，是否创建，默认true
     * @return bool
     */
    public function reName($newname, $auto_build = true)
    {
        if ($auto_build) {
            $dir = dirname($newname);
            new Directory($dir, true, false);
        }
        return rename($this->_path, $newname);
    }

    /**
     * 对于已有的 target 建立一个名为 link 的符号连接。(不能运行在windows环境下)
     * @param string $target 目标路径
     * @return bool
     */
    public function symLinkTo($target)
    {
        return symlink($target, $this->_path);
    }

    /**
     * 设定文件的访问和修改时间
     * 注意，如果文件不存在则尝试创建
     * @param int $time 要设定的修改时间
     * @param int $atime 要设定的访问时间
     * @return bool
     */
    public function setTouch($time = null, $atime = null)
    {
        if (is_null($time)) {
            $time = time();
        }
        return touch($this->_path, $time, $atime);
    }

    /**
     * 改变当前的 umask
     * @param int $mask
     * @return int
     */
    public static function changeUmask($mask)
    {
        return umask($mask);
    }

    /**
     * 获取当前的 umask
     * @return int
     */
    public static function getUmask()
    {
        return umask();
    }

    /**
     * 设置当前打开文件的缓冲大小。
     * @param int $buffer 规定缓冲大小，以字节计。
     * @return mixed 为启动句柄时返回false；否则如果成功，该函数返回 0，否则返回 EOF。
     */
    public function setBuffer($buffer)
    {
        if ($this->_resource) {
            return set_file_buffer($this->_resource, $buffer);
        } else {
            return false;
        }
    }
}
