# 目录 Directory

> 命名空间：`Fize\IO`
> 源文件：`src/Directory.php`

`Directory` 类提供目录操作功能，包括目录的打开、关闭、遍历、扫描、创建、删除、清理等。

---

## 构造与析构

### `__construct(string $path, bool $auto_build = false)`

| 参数 | 类型 | 说明 |
|---|---|---|
| `$path` | `string` | 目录路径 |
| `$auto_build` | `bool` | 指定路径不存在时是否创建，默认 `false` |

- 构造时会对路径进行规范化处理。

### `__destruct()`

析构时自动关闭目录句柄。

---

## 实例方法

### `open()`

打开当前目录。对应 `opendir()`。

---

### `close()`

关闭当前目录。对应 `closedir()`。

---

### `read(callable $func, bool $filter_base = false)`

遍历当前目录的文件条目。对应 `readdir()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$func` | `callable` | 遍历回调函数，参数为条目名称 `($item)` |
| `$filter_base` | `bool` | 是否剔除 `.` 和 `..`，默认 `false` |

> 需先调用 `open()` 打开目录。

---

### `rewind()`

将当前目录流重置到目录的开头。对应 `rewinddir()`。

---

### `clear(string $path = null): bool`

清理指定文件夹（递归删除其下所有文件和子目录，但不删除文件夹本身）。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$path` | `string\|null` | 目录路径，不指定则为当前目录 |

- 返回是否全部删除成功。

---

### `scan(int $sorting_order = 0): array`

列出路径中的文件和目录（含 `.` 和 `..`）。对应 `scandir()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$sorting_order` | `int` | 排序方式，`0` 升序，`1` 降序 |

---

### `tempnam(string $prefix = ''): string`

在当前文件夹建立一个具有唯一文件名的文件。对应 `tempnam()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$prefix` | `string` | 临时文件前缀 |

- 返回完整的临时文件路径，失败时抛出 `RuntimeException`。

---

### `create(int $mode = 0775, bool $recursive = false, $context = null): bool`

创建当前目录。对应 `mkdir()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$mode` | `int` | 访问权限，默认 `0775` |
| `$recursive` | `bool` | 是否递归创建多层目录 |
| `$context` | `resource` | 上下文支持 |

---

### `delete(bool $force = false): bool`

删除当前文件夹。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$force` | `bool` | 目录不为空时是否强制删除（会先清空再删除），默认 `false` |

- 目录不存在时返回 `true`。

---

## 静态方法

### `exists(string $path): bool`

判断目录是否存在。

- 在 Windows 下严格遵守大小写（原生文件系统不区分大小写）。

---

### `realpath(string $path, bool $check = true): string`

返回规范化的绝对路径名。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$path` | `string` | 路径 |
| `$check` | `bool` | 是否检测路径真实有效 |

- `$check = true` 时，路径不存在则抛出 `RuntimeException`。
- `$check = false` 时，对不存在的路径会进行规范化处理（解析 `..`、`.` 等），返回预期的绝对路径。
