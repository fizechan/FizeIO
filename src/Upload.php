<?php

namespace Fize\IO;

use Closure;
use OutOfBoundsException;
use RuntimeException;

/**
 * 上传
 */
class Upload
{

    /**
     * @var array 上传文件信息
     */
    protected $file;

    /**
     * @var array 配置
     */
    protected $config;

    /**
     * @var string 保存文件的完整路径
     */
    protected $path;

    /**
     * @var array 单例配置
     */
    protected static $staticConfig = [];

    /**
     * 初始化
     * @param mixed $file   文件输入框名或者$_FILES数组
     * @param array $config 配置
     */
    public function __construct($file, array $config = [])
    {
        if (is_string($file)) {
            if (!isset($_FILES[$file])) {
                throw new OutOfBoundsException($this->errorForUpload(4));  // 没有该上传文件
            }
            $file = $_FILES[$file];
        }
        $this->file = $file;
        $default_config = [
            'size'    => 2 * 1024 * 1024,  // 单个上传文件的最大字节
            'ext'     => null,  // 文件后缀，多个用逗号分割或者数组
            'type'    => null,  // 文件MIME类型，多个用逗号分割或者数组
            'rule'    => 'date',  // 上传文件保存规则
            'dir'     => './upload',  // 上传文件保存目录
            'name'    => true,  // 保存的文件名。特殊值：true：自动生成（默认）；false(或者'')：保留原文件名
            'replace' => true,  // 同名文件是否覆盖
            'autoext' => true,  // 自动补充扩展名
        ];
        $config = array_merge($default_config, $config);
        $this->config = $config;
    }

    /**
     * 文件路径
     * @return string
     */
    public function path(): string
    {
        $path = $this->path;
        return str_replace('\\', '/', $path);
    }

    /**
     * 保存文件
     * @return File 返回File实例
     */
    public function save(): File
    {
        if (!empty($this->file['error'])) {
            throw new RuntimeException($this->errorForUpload($this->file['error']));
        }

        $dir = $this->config['dir'];
        $savename = $this->config['name'];
        $replace = $this->config['replace'];
        $auto_append_ext = $this->config['autoext'];

        // 验证上传
        $this->checkUpload();

        $dir = rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $savename = $this->buildSaveName($savename, $auto_append_ext);  // 文件保存命名规则
        $filename = $dir . $savename;
        $this->path = $filename;

        // 检测目录
        $this->checkPath(dirname($filename));

        // 不覆盖同名文件
        if (!$replace && is_file($filename)) {
            throw new RuntimeException("has the same filename: $filename");
        }

        // 移动文件
        if (!move_uploaded_file($this->file['tmp_name'], $filename)) {
            throw new RuntimeException('upload write error');
        }

        return new File($filename);
    }

    /**
     * 获取上传错误代码信息
     * @param int $error_no 错误号
     * @return string
     */
    private function errorForUpload(int $error_no): string
    {
        switch ($error_no) {
            case 1:
            case 2:
                $error = 'upload File size exceeds the maximum value';
                break;
            case 3:
                $error = 'only the portion of file is uploaded';
                break;
            case 4:
                $error = 'no file to uploaded';
                break;
            case 6:
                $error = 'upload temp dir not found';
                break;
            case 7:
                $error = 'file write error';
                break;
            default:
                $error = 'unknown upload error';
        }
        return $error;
    }

    /**
     * 检测上传文件
     */
    protected function checkUpload()
    {
        // 检测合法性
        $this->checkIsUpload();

        if ($this->config['size']) {
            $this->checkSize($this->config['size']);
        }
        if ($this->config['type']) {
            $this->checkMime($this->config['type']);
        }
        if ($this->config['ext']) {
            $this->checkExt($this->config['ext']);
        }

        $this->checkImg();
    }

    /**
     * 检测是否合法的上传文件
     */
    protected function checkIsUpload()
    {
        $file = new File($this->file['tmp_name']);
        $bool = $file->isUploadedFile();
        unset($file);
        if (!$bool) {
            throw new RuntimeException('upload illegal files');
        }
    }

    /**
     * 检测上传文件大小
     * @param int $size 最大大小
     */
    protected function checkSize(int $size)
    {
        if ($this->file['size'] > $size) {
            throw new RuntimeException('filesize not match');
        }
    }

    /**
     * 检测上传文件类型
     * @param mixed $mime 允许类型
     */
    protected function checkMime($mime)
    {
        if (is_string($mime)) {
            $mime = explode(',', $mime);
        }

        if (!in_array(strtolower($this->file['type']), $mime)) {
            throw new RuntimeException('mimetype to upload is not allowed');
        }
    }

    /**
     * 检测上传文件后缀
     * @param mixed $ext 允许后缀
     */
    protected function checkExt($ext)
    {
        if (is_string($ext)) {
            $ext = explode(',', $ext);
        }

        $extension = strtolower(pathinfo($this->file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $ext)) {
            throw new RuntimeException('extensions to upload is not allowed');
        }
    }

    /**
     * 检测图像文件
     */
    protected function checkImg()
    {
        $extension = strtolower(pathinfo($this->file['name'], PATHINFO_EXTENSION));
        // 对图像文件进行严格检测
        if (
            in_array($extension, ['gif', 'jpg', 'jpeg', 'bmp', 'png', 'swf']) &&
            !in_array($this->getImageType($this->file['tmp_name']), [1, 2, 3, 4, 6, 13])
        ) {
            throw new RuntimeException('illegal image files');
        }
    }

    /**
     * 判断图像类型
     * @param string $image 图片文件路径
     * @return int
     */
    protected function getImageType(string $image): int
    {
        if (function_exists('exif_imagetype')) {
            return exif_imagetype($image);
        }
        $info = getimagesize($image);
        return $info ? $info[2] : 0;
    }


    /**
     * 获取保存文件名
     *
     * 参数 `$savename` :
     *   特殊值：true：自动生成（默认）；false(或者'')：保留原文件名
     * @param string|bool $savename        保存的文件名
     * @param bool        $auto_append_ext 自动补充扩展名
     * @return string
     */
    protected function buildSaveName($savename, bool $auto_append_ext = true): string
    {
        if (true === $savename) {  // 自动生成文件名
            $savename = $this->autoBuildName();
        } elseif ('' === $savename || false === $savename) {  // 保留原文件名
            $savename = $this->file['name'];
        }

        if ($auto_append_ext && false === strpos($savename, '.')) {
            $savename .= '.' . pathinfo($this->file['name'], PATHINFO_EXTENSION);
        }

        return $savename;
    }

    /**
     * 自动生成文件名
     * @return string
     */
    protected function autoBuildName(): string
    {
        if ($this->config['rule'] instanceof Closure) {
            $savename = call_user_func_array($this->config['rule'], [$this->file]);
        } else {
            switch ($this->config['rule']) {
                case 'date':
                    $savename = date('Ymd') . DIRECTORY_SEPARATOR . md5(microtime(true));
                    break;
                default:
                    if (in_array($this->config['rule'], hash_algos())) {
                        $hash = $this->hash($this->config['rule']);
                        $savename = substr($hash, 0, 2) . DIRECTORY_SEPARATOR . substr($hash, 2);
                    } elseif (is_callable($this->config['rule'])) {
                        $savename = call_user_func($this->config['rule']);
                    } else {
                        $savename = date('Ymd') . DIRECTORY_SEPARATOR . md5(microtime(true));
                    }
            }
        }

        return $savename;
    }

    /**
     * 获取文件的哈希散列值
     * @param string $type 算法名称
     * @return string
     */
    protected function hash(string $type): string
    {
        return hash_file($type, $this->file['tmp_name']);
    }

    /**
     * 检查目录是否可写
     * @param string $path 目录
     */
    protected function checkPath(string $path)
    {
        if (is_dir($path)) {
            return;
        }
        if (mkdir($path, 0755, true)) {
            return;
        }
        throw new RuntimeException("directory `$path` creation failed");
    }

    /**
     * 单例配置
     * @param array $config 配置
     */
    public static function config(array $config = [])
    {
        self::$staticConfig = array_merge(self::$staticConfig, $config);
    }

    /**
     * 简易模式下的单文件上传
     * @param mixed $file   文件输入框名或者$_FILES数组
     * @param array $config 配置
     * @return array [file, path, error]
     */
    public static function single($file, array $config = []): array
    {
        $config = array_merge(self::$staticConfig, $config);
        $upload = new static($file, $config);
        $file = $upload->save();
        $path = $upload->path();
        return [
            'file' => $file,
            'path' => $path
        ];
    }

    /**
     * 简易模式下的多文件上传
     * @param mixed $files  多文件输入框名、文件输入框名数组或者符合$_FILES格式的数组
     * @param array $config 配置
     * @return array
     */
    public static function multiple($files, array $config = []): array
    {
        if (is_string($files)) {
            $files = self::reArrayFiles($_FILES[$files]);
        }
        $results = [];
        foreach ($files as $file) {
            $results[] = self::single($file, $config);
        }
        return $results;
    }

    /**
     * 将原生的$_FILES多文件数组转化为方便读取的多文件数组
     * @param array $file_post 原生的$_FILES多文件数组
     * @return array
     */
    protected static function reArrayFiles(array $file_post): array
    {
        $file_ary = [];
        $file_count = count($file_post['name']);
        $file_keys = array_keys($file_post);

        for ($i = 0; $i < $file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $file_post[$key][$i];
            }
        }
        return $file_ary;
    }
}
