# 文件 File

> 命名空间：`Fize\IO`
> 源文件：`src/File.php`

`File` 类继承自 PHP 内置的 `SplFileObject`，提供文件系统级操作。与 [FileAbstract](FileAbstract.md) 体系不同，`File` 不基于 PHP 文件资源，而是以 OOP 方式封装文件路径，提供属性查询、权限管理、内容读写、链接操作等功能。

> 注意：`File` 与 `FileAbstract`/`FileF`/`FileP` 是**并行的、独立的两条继承线**，不共享基类。

---

## 构造方法

### `__construct($filename, $mode = 'r', $useIncludePath = false, $context = null)`

| 参数 | 类型 | 说明 |
|---|---|---|
| `$filename` | `string` | 文件路径 |
| `$mode` | `string` | 打开模式，默认 `'r'` |
| `$useIncludePath` | `bool` | 是否在 include_path 中搜索文件 |
| `$context` | `resource` | 上下文 |

- 写入模式（含 `w`、`a`、`x`、`+`）下，**自动创建父级目录**。
- 非 URL 路径时，会规范化为绝对路径。

---

## 实例方法

### `chgrp($group): bool`

改变文件所属的组。该函数不能在 Windows 系统上运行。只有超级用户可以任意修改文件的组，其它用户可能只能将文件的组改成该用户自己所在的组。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$group` | `mixed` | 组的名称或数字 |

---

### `chmod(int $mode): bool`

改变文件模式。注意 `mode` 不会被自动当成八进制数值，需要加前缀 `0`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$mode` | `int` | 模式值，如 `0755` |

---

### `chown($user): bool`

改变文件的所有者。该函数不能在 Windows 系统上运行。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$user` | `mixed` | 用户名或数字 |

---

### `clearstatcache()`

清除当前文件状态缓存。

---

### `copy(string $dir, string $name = null, bool $cover = false): bool`

将当前文件拷贝到指定目录。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$dir` | `string` | 目标文件夹路径 |
| `$name` | `string\|null` | 指定文件名，不指定则为原文件名 |
| `$cover` | `bool` | 目标文件已存在时是否覆盖 |

- 若目标目录不存在，会自动创建。

---

### `delete($context = null): bool`

删除文件。等价于 `unlink()` 方法。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$context` | `resource` | 上下文 |

---

### `getContents(bool $use_include_path = false, $context = null, int $offset = 0, int $maxlen = null): string`

将整个文件读入一个字符串。对应 `file_get_contents()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$use_include_path` | `bool` | 是否在 include_path 中搜寻文件 |
| `$context` | `resource` | 上下文支持 |
| `$offset` | `int` | 读取起始偏移量，默认 0 |
| `$maxlen` | `int\|null` | 指定读取最大长度，默认全部读取 |

- 失败时抛出 `RuntimeException`。

---

### `putContents($data, int $flags = 0, $context = null): int`

将一个字符串写入文件。对应 `file_put_contents()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$data` | `mixed` | 要写入的数据（string、array 或 stream 资源） |
| `$flags` | `int` | 配置，可选值：`FILE_USE_INCLUDE_PATH`、`FILE_APPEND`、`LOCK_EX` |
| `$context` | `resource` | 上下文支持 |

- 返回写入的字节数，失败时抛出 `RuntimeException`。

---

### `nmatch(string $pattern, int $flags = 0): bool`

检查文件名是否与通配符模式匹配。使用 `fnmatch()` 实现。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$pattern` | `string` | 通配符模式（shell 风格，如 `*.txt`） |
| `$flags` | `int` | 可选值：`FNM_NOESCAPE`、`FNM_PATHNAME`、`FNM_PERIOD`、`FNM_CASEFOLD` |

---

### `isUploadedFile(): bool`

判断当前文件是否是通过 HTTP POST 上传的。

---

### `link(string $link): bool`

建立一个硬连接。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$link` | `string` | 链接的名称 |

---

### `linkinfo(): int`

获取一个连接的信息。失败时抛出 `RuntimeException`。

---

### `readfile(bool $use_include_path = false, $context = null): int`

读取文件并写入到输出缓冲。返回输出的字节数。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$use_include_path` | `bool` | 是否在 include_path 中搜索文件 |
| `$context` | `resource` | 上下文支持 |

---

### `readlink(): string`

返回符号连接指向的目标。失败时抛出 `RuntimeException`。

---

### `rename(string $newname, bool $auto_build = true): bool`

重命名/移动文件。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$newname` | `string` | 目标位置路径 |
| `$auto_build` | `bool` | 目标路径不存在时是否自动创建目录，默认 `true` |

---

### `symlink(string $link): bool`

建立一个符号连接。Windows 下需要超级管理员权限。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$link` | `string` | 链接的名称 |

---

### `touch(int $time = null, int $atime = null): bool`

设定文件的访问和修改时间。如果文件不存在则尝试创建。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$time` | `int\|null` | 修改时间，默认当前时间 |
| `$atime` | `int\|null` | 访问时间 |

---

### `unlink($context = null): bool`

删除文件。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$context` | `resource` | 上下文 |

---

### `getMime(): string`

返回文件的 MIME 类型。优先使用 `fileinfo` 扩展，其次使用 `mime_content_type()`，均不可用时返回 `'application/octet-stream'`。

---

### `getExtension(): string`

尽可能返回文件后缀名。对于已有后缀名的文件直接返回；无后缀名文件通过 MIME 猜测后缀名（不保证准确性）。无后缀名时返回空字符串。

---

### `split(string $targetDir, int $partSize, string $prefix = 'part_'): array`

按文件大小分割文件。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$targetDir` | `string` | 目标文件夹路径 |
| `$partSize` | `int` | 每个分割文件的大小，单位 MB |
| `$prefix` | `string` | 分割文件的前缀名 |

- 返回分割文件路径数组。
- 目标目录不存在时自动创建。

---

## 静态方法

### `exists(string $path): bool`

检查文件是否存在。在 Windows 下严格遵守大小写（原生文件系统不区分大小写）。

---

### `realpath(string $path, bool $check = true): string`

返回规范化的绝对路径名。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$path` | `string` | 路径 |
| `$check` | `bool` | 是否检测路径真实有效 |

- `$check = true` 时，路径不存在则抛出 `RuntimeException`。
- `$check = false` 时，对不存在的路径会拼接出预期的绝对路径。
