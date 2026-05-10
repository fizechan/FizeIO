# 原生文件 FileF

> 命名空间：`Fize\IO`
> 源文件：`src/FileF.php`
> 父类：[FileAbstract](FileAbstract.md)

`FileF` 继承自 [FileAbstract](FileAbstract.md)，基于 `fopen()`/`fclose()` 实现文件打开与关闭，并提供 CSV 读写支持。适用于需要以 PHP 原生文件资源方式操作文件的场景。

---

## 构造与析构

### `__construct($stream = null)`

继承自 [FileAbstract](FileAbstract.md)。可传入已有的文件流资源。

### `__destruct()`

析构时自动关闭文件流，防止资源泄漏。

---

## 实例方法

### `open(string $file, string $mode = null, bool $use_include_path = false, $context = null)`

打开文件。对应 `fopen()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$file` | `string` | 文件路径 |
| `$mode` | `string\|null` | 打开模式 |
| `$use_include_path` | `bool` | 是否在 include_path 中搜寻文件 |
| `$context` | `resource` | 上下文支持 |

- 若当前已有未关闭的流，抛出 `RuntimeException`。
- 写入模式（`r+`、`w`、`w+`、`a`、`a+`、`x`、`x+`）下，**自动创建父级目录**。

---

### `close(): bool`

关闭文件。对应 `fclose()`。成功关闭后清空内部流资源引用。

- 如果流已不是有效资源，直接返回 `true`。

---

### `getcsv(int $length = 0, string $delimiter = ',', string $enclosure = '"', string $escape = "\\"): array`

从文件指针中读入一行并解析 CSV 字段。对应 `fgetcsv()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$length` | `int` | 行的最大长度，必须大于 CSV 文件内最长的一行 |
| `$delimiter` | `string` | 字段分界符（单字符），默认逗号 |
| `$enclosure` | `string` | 字段环绕符（单字符），默认双引号 |
| `$escape` | `string` | 转义字符（单字符），默认反斜杠 |

- 失败时抛出 `RuntimeException`。

---

### `putcsv(array $fields, string $delimiter = ',', string $enclosure = '"', string $escape_char = "\\"): int`

将行格式化为 CSV 并写入文件指针。对应 `fputcsv()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$fields` | `array` | 要写入的数组数据 |
| `$delimiter` | `string` | 分隔符 |
| `$enclosure` | `string` | 界限符 |
| `$escape_char` | `string` | 转义符 |

- 返回写入的字符串长度，失败时抛出 `RuntimeException`。

---

## 继承的方法

从 [FileAbstract](FileAbstract.md) 继承的所有方法均可使用：`eof()`、`flush()`、`getc()`、`gets()`、`getss()`、`lock()`、`passthru()`、`puts()`、`read()`、`scanf()`、`seek()`、`tell()`、`truncate()`、`write()`、`rewind()`、`setBuffer()`。
