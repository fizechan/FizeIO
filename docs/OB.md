# 输出缓冲区 OB

> 命名空间：`Fize\IO`
> 源文件：`src/OB.php`

`OB` 类封装 PHP 输出缓冲区控制函数，所有方法均为静态方法，无需实例化。

---

## 静态方法

### `clean()`

丢弃输出缓冲区中的内容。此方法不会销毁输出缓冲区。对应 `ob_clean()`。

---

### `endClean(): bool`

清空（擦除）缓冲区并关闭输出缓冲。对应 `ob_end_clean()`。

---

### `endFlush(): bool`

输出缓冲区内容并关闭缓冲。对应 `ob_end_flush()`。

---

### `flush()`

输出缓冲区中的内容。同时调用 `ob_flush()` 和 `flush()`。

---

### `getClean(): string`

得到当前缓冲区的内容并删除当前输出缓冲区。对应 `ob_get_clean()`。

- 失败时抛出 `RuntimeException`。

---

### `getContents(): string`

返回输出缓冲区的内容。对应 `ob_get_contents()`。

- 失败时抛出 `RuntimeException`。

---

### `getFlush(): string`

输出缓冲区内容，以字符串形式返回内容，并关闭输出缓冲区。对应 `ob_get_flush()`。

- 失败时抛出 `RuntimeException`。

---

### `getLength(): int`

返回输出缓冲区内容的长度。对应 `ob_get_length()`。

- 失败时抛出 `RuntimeException`。

---

### `getLevel(): int`

返回输出缓冲机制的嵌套级别。如果输出缓冲区不起作用，返回 `0`。对应 `ob_get_level()`。

---

### `getStatus(bool $full_status = false): array`

获取缓冲区的状态信息。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$full_status` | `bool` | 是否返回所有有效的输出缓冲级别 |

- `$full_status = false`：返回最顶层输出缓冲区的状态信息。
- `$full_status = true`：返回所有有效的输出缓冲级别。

---

### `gzhandler(string $buffer, int $mode): string`

在 `OB::start()` 中使用的用来压缩输出缓冲区中内容的回调函数。对应 `ob_gzhandler()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$buffer` | `string` | 待输出缓冲区内容 |
| `$mode` | `int` | 指定模式 |

- 需要启用 `zlib` 扩展。
- 失败时抛出 `RuntimeException`。

---

### `implicitFlush(bool $flag = true)`

打开/关闭绝对刷送。对应 `ob_implicit_flush()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$flag` | `bool` | `true` 打开绝对刷送，`false` 关闭 |

- 开启后每次输出调用后自动刷送，不再需要显式调用 `flush()`。

---

### `listHandlers(): array`

列出所有使用中的输出处理程序。对应 `ob_list_handlers()`。

---

### `start(callable $output_callback = null, int $chunk_size = 0, bool $erase = true): bool`

打开输出控制缓冲。对应 `ob_start()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$output_callback` | `callable\|null` | 缓冲区内容变化时的回调函数 |
| `$chunk_size` | `int` | 缓冲区大小，默认 `0` 表示函数仅在最后被调用 |
| `$erase` | `bool` | 如果设为 `false`，直到脚本执行完成缓冲区才被删除 |
