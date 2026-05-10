# 文件基类 FileAbstract

> 命名空间：`Fize\IO`
> 源文件：`src/FileAbstract.php`

`FileAbstract` 是基于 PHP 文件资源（`resource`）的抽象基类，将 PHP 的 `f*()` 系列函数封装为 OOP 接口。其子类 [FileF](FileF.md) 和 [FileP](FileP.md) 分别提供 `fopen` 和 `popen` 两种打开方式。

> 注意：[File](File.md) 继承自 `SplFileObject`，与 `FileAbstract` 是**完全独立**的两条继承线。

---

## 类属性

| 属性 | 类型 | 说明 |
|---|---|---|
| `$stream` | `resource` | 文件流资源（`protected`） |

---

## 构造方法

### `__construct($stream = null)`

| 参数 | 类型 | 说明 |
|---|---|---|
| `$stream` | `resource` | 已有的文件流资源，可选 |

---

## 实例方法

### `getStream(): resource|null`

返回当前上下文的文件流资源。不存在时返回 `null`。

> 请谨慎使用该方法，直接操作底层资源可能破坏封装。

---

### `eof(): bool`

测试文件指针是否到了文件结束的位置。对应 `feof()`。

---

### `flush(): bool`

将缓冲内容输出到文件。对应 `fflush()`。

---

### `getc(): string`

从文件指针中读取一个字符。对应 `fgetc()`。失败时抛出 `RuntimeException`。

---

### `gets(int $length = null): string`

从文件指针中读取一行。对应 `fgets()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$length` | `int\|null` | 读取的最大字节数，默认 1024，实际返回 `$length - 1` 字节 |

- 失败时抛出 `RuntimeException`。

---

### `getss(int $length = null, string $allowable_tags = null): string`

从文件指针中读取一行并过滤掉 HTML 和 PHP 标记。对应 `fgetss()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$length` | `int\|null` | 读取的最大字节数 |
| `$allowable_tags` | `string\|null` | 不被删除的标签，如 `"<p>,<b>"` |

> **已弃用**：PHP 7.3 起不建议使用该方法。

---

### `lock(int $operation, int &$wouldblock = null): bool`

轻便的咨询文件锁定。对应 `flock()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$operation` | `int` | 操作，可选值：`LOCK_SH`、`LOCK_EX`、`LOCK_UN` |
| `$wouldblock` | `int` | 如果锁定会堵塞则设为 1 |

---

### `passthru(): int`

输出文件指针处的所有剩余数据。对应 `fpassthru()`。返回剩余数据字节数。

---

### `puts(string $string, int $length = null): int`

写入文件（可安全用于二进制文件）。对应 `fputs()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$string` | `string` | 要写入的字符串 |
| `$length` | `int\|null` | 指定写入长度 |

- 返回写入的字节数，失败时抛出 `RuntimeException`。

---

### `read(int $length): string`

读取文件（可安全用于二进制文件）。对应 `fread()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$length` | `int` | 读取的最大字节数 |

- 失败时抛出 `RuntimeException`。

---

### `scanf(string $format): array|int|null`

从文件中格式化输入。对应 `fscanf()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$format` | `string` | 格式字符串 |

---

### `seek(int $offset, int $whence = 0): int`

在文件指针中定位。对应 `fseek()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$offset` | `int` | 偏移量 |
| `$whence` | `int` | 设置方式：`SEEK_SET`、`SEEK_CUR`、`SEEK_END` |

---

### `tell(): int`

返回文件指针读/写的位置。对应 `ftell()`。失败时抛出 `RuntimeException`。

---

### `truncate(int $size): bool`

将文件截断到给定的长度。对应 `ftruncate()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$size` | `int` | 指定长度 |

---

### `write(string $string, int $length = null): int`

写入文件（可安全用于二进制文件）。对应 `fwrite()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$string` | `string` | 要写入的字符串 |
| `$length` | `int\|null` | 指定写入长度 |

- 返回写入的字节数，失败时抛出 `RuntimeException`。

---

### `rewind(): bool`

倒回文件指针的位置。对应 `rewind()`。

---

### `setBuffer(int $buffer): int`

设置当前打开文件的缓冲大小。对应 `set_file_buffer()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$buffer` | `int` | 缓冲大小，以字节计 |
