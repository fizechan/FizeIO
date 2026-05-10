# 输出 Output

> 命名空间：`Fize\IO`
> 源文件：`src/Output.php`

`Output` 类封装输出 URL 重写控制函数，所有方法均为静态方法，无需实例化。

---

## 静态方法

### `addRewriteVar(string $name, string $value): bool`

添加 URL 重写器的值。对应 `output_add_rewrite_var()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$name` | `string` | 变量名 |
| `$value` | `string` | 变量值 |

- 此函数会给当前作用域内的 URL 添加指定的名值对作为查询参数。

---

### `resetRewriteVars(): bool`

重设 URL 重写器的值。对应 `output_reset_rewrite_vars()`。

- 清除所有之前通过 `addRewriteVar()` 设置的重写变量。
