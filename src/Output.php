<?php

namespace fize\io;

/**
 * 输出
 */
class Output
{
    /**
     * 添加URL重写器的值
     * @param string $name  变量名。
     * @param string $value 变量值。
     * @return bool
     */
    public static function addRewriteVar(string $name, string $value): bool
    {
        return output_add_rewrite_var($name, $value);
    }

    /**
     * 重设URL重写器的值
     * @return bool
     */
    public static function resetRewriteVars(): bool
    {
        return output_reset_rewrite_vars();
    }
}