<?php

namespace Fize\IO;

/**
 * 缓冲区
 */
class OB
{

    /**
     * 丢弃输出缓冲区中的内容
     *
     * 此方法不会销毁输出缓冲区
     */
    public static function clean()
    {
        ob_clean();
    }

    /**
     * 清空（擦除）缓冲区并关闭输出缓冲
     * @return bool 成功时返回TRUE， 或者在失败时返回FALSE。
     */
    public static function endClean(): bool
    {
        return ob_end_clean();
    }

    /**
     * 输出缓冲区内容并关闭缓冲
     * @return bool 成功时返回TRUE， 或者在失败时返回FALSE。
     */
    public static function endFlush(): bool
    {
        return ob_end_flush();
    }

    /**
     * 输出缓冲区中的内容
     */
    public static function flush()
    {
        ob_flush();
        flush();
    }

    /**
     * 得到当前缓冲区的内容并删除当前输出缓冲区。
     * @return string 当前缓冲区的内容
     */
    public static function getClean(): string
    {
        return ob_get_clean();
    }

    /**
     * 返回输出缓冲区的内容
     * @return string 如果输出缓冲区无效将返回FALSE。
     */
    public static function getContents(): string
    {
        return ob_get_contents();
    }

    /**
     * 输出缓冲区内容，以字符串形式返回内容，并关闭输出缓冲区。
     * @return string 如果没有起作用的输出缓冲区，返回FALSE。
     */
    public static function getFlush(): string
    {
        return ob_get_flush();
    }

    /**
     * 返回输出缓冲区内容的长度
     * @return int
     */
    public static function getLength(): int
    {
        return ob_get_length();
    }

    /**
     * 返回输出缓冲机制的嵌套级别
     * @return int 如果输出缓冲区不起作用，返回0。
     */
    public static function getLevel(): int
    {
        return ob_get_level();
    }

    /**
     * 获取缓冲区的状态信息
     *
     * 返回最顶层输出缓冲区的状态信息；
     * 或者如果full_status设为TRUE，返回所有有效的输出缓冲级别。
     * @param bool $full_status 是否返回所有有效的输出缓冲级别。
     * @return array
     */
    public static function getStatus(bool $full_status = false): array
    {
        return ob_get_status($full_status);
    }

    /**
     * 在Ob::start()中使用的用来压缩输出缓冲区中内容的回调函数
     *
     * 使用该方法必须启用 zlib 扩展
     * @notice 未讲过手动调用该方法的情况
     * @param string $buffer 待输出缓冲区内容
     * @param int    $mode   指定模式
     * @return string 如果一个浏览器不支持压缩过的页面，此函数返回FALSE。
     * @noinspection PhpComposerExtensionStubsInspection
     */
    public static function gzhandler(string $buffer, int $mode): string
    {
        return ob_gzhandler($buffer, $mode);
    }

    /**
     * 打开/关闭绝对刷送
     *
     * 绝对（隐式）刷送将导致在每次输出调用后有一次刷送操作，以便不再需要对 flush() 的显式调用
     * @param bool $flag 设为TRUE 打开绝对刷送，反之是 FALSE。
     */
    public static function implicitFlush(bool $flag = true)
    {
        ob_implicit_flush($flag);
    }

    /**
     * 列出所有使用中的输出处理程序。
     * @return array
     */
    public static function listHandlers(): array
    {
        return ob_list_handlers();
    }

    /**
     * 打开输出控制缓冲
     * @param callable|null $output_callback 缓冲区内容发生变化时的回调函数
     * @param int           $chunk_size      缓冲区大小，默认0表示函数仅在最后被调用
     * @param bool          $erase           如果可选参数 erase 被赋成 FALSE，直到脚本执行完成缓冲区才被删除
     * @return bool
     */
    public static function start(callable $output_callback = null, int $chunk_size = 0, bool $erase = true): bool
    {
        if (!$output_callback) {
            return ob_start();
        }
        return ob_start($output_callback, $chunk_size, $erase);
    }
}
