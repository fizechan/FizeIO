<?php

namespace Fize\IO;

/**
 * MIME
 */
class MIME
{

    /**
     * @var array 常见MIME对应的后缀名
     * @see http://svn.apache.org/repos/asf/httpd/httpd/branches/1.3.x/conf/mime.types
     */
    protected static $mimesExtensions;

    protected static $cacheHasMimesHasMultipleExtensions = [];

    protected static $cacheGetMimesHasMultipleExtensions = [];

    protected static $cacheGetExtensionsMimes = [];

    protected static $cacheGetMimeByExtension = [];

    /**
     * 从JSON文件加载MIME对应的后缀名
     */
    protected static function loadMimesExtensions(): void
    {
        if (self::$mimesExtensions === null) {
            self::$mimesExtensions = json_decode(file_get_contents(__DIR__ . '/../assets/mimes.json'), true);
        }
    }

    /**
     * 返回MIME对应后缀名
     * @param string $mime      MIME
     * @param bool   $onlyFirst 如果是多个，是否只返回第一个。
     * @return string|null 获取不到返回null
     */
    public static function getExtensionByMime(string $mime, bool $onlyFirst = false): ?string
    {
        self::loadMimesExtensions();

        if (!isset(self::$mimesExtensions[$mime])) {
            return null;
        }
        $extension = self::$mimesExtensions[$mime];
        if ($onlyFirst) {
            $extensions = explode(',', $extension);
            $extension = $extensions[0];
        }
        return $extension;
    }

    /**
     * 根据后缀名返回对应的MIME
     * @param string $extension 后缀名
     * @return string|null
     */
    public static function getMimeByExtension(string $extension): ?string
    {
        if (isset(self::$cacheGetMimeByExtension[$extension])) {
            return self::$cacheGetMimeByExtension[$extension];
        }

        $ems = self::getExtensionsMimes();
        if (isset($ems[$extension])) {
            self::$cacheGetMimeByExtension[$extension] = $ems[$extension];
            return $ems[$extension];
        }
        $mmes = self::getMimesHasMultipleExtensions();
        foreach ($mmes as $mime => $ext) {
            $exts = explode(',', $ext);
            foreach ($exts as $ext2) {
                if ($ext2 === $extension) {
                    self::$cacheGetMimeByExtension[$extension] = $mime;
                    return $mime;
                }
            }
        }
        self::$cacheGetMimeByExtension[$extension] = null;
        return null;
    }

    /**
     * MIME是否对应多个后缀名
     * @param string $mime MIME
     * @return bool|null 多个后缀名返回true,否则返回false，未知返回null。
     */
    public static function hasMultipleExtensions(string $mime): ?bool
    {
        self::loadMimesExtensions();

        if (isset(self::$cacheHasMimesHasMultipleExtensions[$mime])) {
            return self::$cacheHasMimesHasMultipleExtensions[$mime];
        }
        if (!isset(self::$mimesExtensions[$mime])) {
            self::$cacheHasMimesHasMultipleExtensions[$mime] = null;
            return null;
        }
        $extension = self::$mimesExtensions[$mime];
        if (strpos($extension, ',') !== false) {
            self::$cacheHasMimesHasMultipleExtensions[$mime] = true;
        } else {
            self::$cacheHasMimesHasMultipleExtensions[$mime] = false;
        }
        return self::$cacheHasMimesHasMultipleExtensions[$mime];
    }

    /**
     * 获取所有有对应多个后缀名的MIME
     * @return array
     */
    protected static function getMimesHasMultipleExtensions(): array
    {
        self::loadMimesExtensions();

        if (self::$cacheGetMimesHasMultipleExtensions) {
            return self::$cacheGetMimesHasMultipleExtensions;
        }

        $result = (array_filter(self::$mimesExtensions, function ($v) {
            return strpos($v, ',') !== false;
        }));
        self::$cacheGetMimesHasMultipleExtensions = $result;
        return self::$cacheGetMimesHasMultipleExtensions;
    }

    /**
     * 获取所有后缀名对应的MIME
     * @return array
     */
    protected static function getExtensionsMimes(): array
    {
        self::loadMimesExtensions();

        if (!self::$cacheGetExtensionsMimes) {
            self::$cacheGetExtensionsMimes = array_flip(self::$mimesExtensions);
        }
        return self::$cacheGetExtensionsMimes;
    }
}