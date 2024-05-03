<?php

namespace Fize\IO;

/**
 * MIME
 */
class MIME
{

    /**
     * @var array 常见MIME对应的后缀名
     */
    protected static $mimeExtensions = [
        'video/3gp'                                                                 => '3gp',
        'application/x-7z-compressed'                                               => '7z',
        'audio/ac3'                                                                 => 'ac3',
        'audio/x-aiff'                                                              => 'aiff',
        'application/vnd.android.package-archive'                                   => 'apk',
        'text/cache-manifest'                                                       => 'appcache',
        'application/x-ms-application'                                              => 'application',
        'video/x-ms-asf'                                                            => 'asf',
        'text/x-asm'                                                                => 'asm',
        'audio/x-au'                                                                => 'au',
        'video/x-msvideo'                                                           => 'avi',
        'image/avif'                                                                => 'avif',
        'application/x-font-bdf'                                                    => 'bdf',
        'image/bmp'                                                                 => 'bmp',
        'image/prs.btif'                                                            => 'btif',
        'application/x-bzip'                                                        => 'bz',
        'application/x-bzip2'                                                       => 'bz2',
        'text/x-c'                                                                  => 'c',
        'application/vnd.clonk.c4group'                                             => 'c4g',
        'application/vnd.ms-cab-compressed'                                         => 'cab',
        'image/cgm'                                                                 => 'cgm',
        'text/coffeescript'                                                         => 'coffee',
        'text/css'                                                                  => 'css',
        'text/csv'                                                                  => 'csv',
        'application/x-apple-diskimage'                                             => 'dmg',
        'application/msword'                                                        => 'doc',
        'application/vnd.ms-word.template.macroEnabled.12'                          => 'docm',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'   => 'docx',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.template'   => 'dotx',
        'video/x-flv'                                                               => 'flv',
        'image/gif'                                                                 => 'gif',
        'application/x-gzip'                                                        => 'gz',
        'video/h261'                                                                => 'h261',
        'video/h263'                                                                => 'h263',
        'video/h264'                                                                => 'h264',
        'text/html'                                                                 => 'html',
        'image/vnd.microsoft.icon'                                                  => 'ico',
        'application/x-iso9660-image'                                               => 'iso',
        'application/java-archive'                                                  => 'jar',
        'text/x-java-source'                                                        => 'java',
        'image/jp2'                                                                 => 'jp2',
        'image/jpeg'                                                                => 'jpg',
        'video/jpeg'                                                                => 'jpgv',
        'image/jph'                                                                 => 'jph',
        'video/jpm'                                                                 => 'jpm',
        'image/jpx'                                                                 => 'jpx',
        'application/javascript'                                                    => 'js',
        'application/json'                                                          => 'json',
        'text/jsx'                                                                  => 'jsx',
        'application/vnd.apple.mpegurl'                                             => 'm3u8',
        'audio/x-m4a'                                                               => 'm4a',
        'video/x-m4v'                                                               => 'm4v',
        'text/markdown'                                                             => 'md',
        'audio/midi'                                                                => 'mid',
        'video/x-matroska'                                                          => 'mkv',
        'video/quicktime'                                                           => 'mov',
        'video/x-sgi-movie'                                                         => 'movie',
        'audio/mpeg'                                                                => 'mp3',
        'video/mp4'                                                                 => 'mp4',
        'video/mpeg'                                                                => 'mpeg',
        'application/x-msdownload'                                                  => 'msi',
        'audio/ogg'                                                                 => 'ogg',
        'video/ogg'                                                                 => 'ogv',
        'application/ogg'                                                           => 'ogx',
        'application/vnd.oasis.opendocument.spreadsheet'                            => 'ods',
        'application/vnd.oasis.opendocument.text'                                   => 'odt',
        'text/x-org'                                                                => 'org',
        'font/otf'                                                                  => 'otf',
        'application/pdf'                                                           => 'pdf',
        'application/x-httpd-php'                                                   => 'php',
        'image/png'                                                                 => 'png',
        'application/powerpoint'                                                    => 'ppt',
        'application/vnd.ms-powerpoint'                                             => 'ppt',
        'application/vnd.ms-powerpoint.presentation.macroEnabled.12'                => 'pptm',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
        'application/postscript'                                                    => 'ps',
        'application/x-photoshop'                                                   => 'psd',
        'image/vnd.adobe.photoshop'                                                 => 'psd',
        'application/x-rar'                                                         => 'rar',
        'application/x-rar-compressed'                                              => 'rar',
        'image/x-rgb'                                                               => 'rgb',
        'audio/x-pn-realaudio'                                                      => 'rm',
        'application/vnd.rn-realmedia-vbr'                                          => 'rmvb',
        'application/x-pkcs7'                                                       => 'rsa',
        'application/rtf'                                                           => 'rtf',
        'audio/s3m'                                                                 => 's3m',
        'image/svg+xml'                                                             => 'svg',
        'application/x-shockwave-flash'                                             => 'swf',
        'image/tiff'                                                                => 'tiff',
        'application/x-bittorrent'                                                  => 'torrent',
        'font/ttf'                                                                  => 'ttf',
        'text/plain'                                                                => 'txt',
        'application/x-virtualbox-vbox'                                             => 'vbox',
        'application/x-virtualbox-vbox-extpack'                                     => 'vbox-extpack',
        'application/vnd.visio'                                                     => 'vsd',
        'image/webp'                                                                => 'webp',
        'video/x-ms-wm'                                                             => 'wm',
        'audio/x-ms-wma'                                                            => 'wma',
        'video/x-ms-wmv'                                                            => 'wmv',
        'font/woff'                                                                 => 'woff',
        'font/woff2'                                                                => 'woff2',
        'application/vnd.ms-excel'                                                  => 'xls',
        'application/vnd.ms-excel.sheet.binary.macroEnabled.12'                     => 'xlsb',
        'application/vnd.ms-excel.sheet.macroEnabled.12'                            => 'xlsm',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'         => 'xlsx',
        'application/xml'                                                           => 'xml',
        'text/yaml'                                                                 => 'yaml',
        'application/zip'                                                           => 'zip',
    ];

    /**
     * @var string MIME
     */
    protected $mime;

    /**
     * 初始化
     * @param string $mime MIME
     */
    public function __construct(string $mime)
    {
        $this->mime = $mime;
    }

    /**
     * 返回对应后缀名
     * @return string|null 获取不到返回null
     */
    public function getExtension(): ?string
    {
        return self::$mimeExtensions[$this->mime] ?? null;
    }

    /**
     * 根据后缀名返回对应的MIME
     * @param string $extension 后缀名
     * @return string|null
     */
    public static function getByExtension(string $extension): ?string
    {
        $ext_mimes = array_flip(self::$mimeExtensions);
        return $ext_mimes[$extension] ?? null;
    }
}