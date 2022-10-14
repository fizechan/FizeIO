<?php

namespace Fize\IO;

/**
 * 后缀名
 */
class Extension
{

    /**
     * 根据后缀名判断是否是图片类型
     * @param string $ext 后缀名
     * @return bool
     */
    public static function isImage(string $ext): bool
    {
        $img_exts = [
            'bmp', 'gif', 'ico', 'iff', 'jb2', 'jp2', 'jpc', 'jpeg', 'jpg', 'jph', 'jpx', 'png', 'psd', 'svg', 'swc', 'swf', 'tiff', 'wbmp', 'webp', 'xbm'
        ];
        return in_array(strtolower($ext), $img_exts);
    }

}