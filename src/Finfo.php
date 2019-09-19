<?php /** @noinspection PhpComposerExtensionStubsInspection */


namespace fize\io;

use finfo as Base;


class Finfo extends Base
{

    /**
     * 检测文件的 MIME 类型
     * @param string $filename 要检测的文件名
     * @return string
     */
    public static function mimeContentType($filename)
    {
        return mime_content_type($filename);
    }
}
