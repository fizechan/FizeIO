# 流 Stream

> 命名空间：`Fize\IO`
> 源文件：`src/Stream.php`
> 父类：[FileF](FileF.md)

`Stream` 继承自 [FileF](FileF.md)，在文件操作的基础上增加流专用功能，包括流复制、内容读取、元数据获取、阻塞/超时设置等。

---

## 实例方法

### `copyToStream($dest, int $maxlength = -1, int $offset = 0): int`

将数据复制到另一个流。对应 `stream_copy_to_stream()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$dest` | `resource` | 目标流资源 |
| `$maxlength` | `int` | 最大复制长度，默认 `-1`（全部） |
| `$offset` | `int` | 偏移量，默认 `0` |

- 返回复制的字节数，失败时抛出 `RuntimeException`。

---

### `getContents(int $maxlength = -1, int $offset = -1): string`

读取资源流到一个字符串。对应 `stream_get_contents()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$maxlength` | `int` | 最大读取字节数，默认 `-1`（读取全部缓冲数据） |
| `$offset` | `int` | 读取前先查找的偏移量，负数则从当前位置开始读取 |

- 失败时抛出 `RuntimeException`。

---

### `getLine(int $length, string $ending = null): string`

从资源流里读取一行直到给定的定界符。对应 `stream_get_line()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$length` | `int` | 需要读取的字节数 |
| `$ending` | `string\|null` | 字符串定界符 |

- 失败时抛出 `RuntimeException`。

---

### `getMetaData(): array`

从封装协议文件指针中取得报头/元数据。对应 `stream_get_meta_data()`。

---

### `isLocal($stream_or_url = null): bool`

检查流是否是本地流。对应 `stream_is_local()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$stream_or_url` | `mixed` | 可以指定流或者 URL，默认使用当前流 |

---

### `isatty(): bool`

确定流是否引用有效的终端类型设备。对应 `stream_isatty()`。

---

### `setBlocking(int $mode): bool`

为资源流设置阻塞或者非阻塞模式。对应 `stream_set_blocking()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$mode` | `int` | 模式，`0` 非阻塞，`1` 阻塞 |

---

### `setChunkSize(int $chunk_size): int`

设置资源流区块大小。对应 `stream_set_chunk_size()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$chunk_size` | `int` | 新的区块大小 |

- 返回新的区块大小，失败时抛出 `RuntimeException`。

---

### `setReadBuffer(int $buffer): int`

设置流上的读取文件缓冲。对应 `stream_set_read_buffer()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$buffer` | `int` | 缓冲大小 |

---

### `setTimeout(int $seconds, int $microseconds = null): bool`

设置流超时时间。对应 `stream_set_timeout()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$seconds` | `int` | 秒数 |
| `$microseconds` | `int\|null` | 微秒数 |

---

### `setWriteBuffer(int $buffer): int`

设置流上的写文件缓冲。对应 `stream_set_write_buffer()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$buffer` | `int` | 缓冲大小 |

---

### `supportsLock(): bool`

表示流是否支持锁定。对应 `stream_supports_lock()`。

---

## 静态方法

### `getFilters(): array`

获取已注册的数据流过滤器列表。对应 `stream_get_filters()`。

> **已弃用**：请使用 `StreamFilter::gets()` 方法。

---

### `getTransports(): array`

获取已注册的套接字传输协议列表。对应 `stream_get_transports()`。

---

### `getWrappers(): array`

获取已注册的流类型。对应 `stream_get_wrappers()`。

> **已弃用**：请使用 `StreamWrapper::gets()` 方法。

---

### `resolveIncludePath(string $filename): string`

根据包含路径解析文件名。对应 `stream_resolve_include_path()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$filename` | `string` | 包含路径的文件名 |

- 失败时抛出 `RuntimeException`。

---

### `select(array &$read, array &$write, array &$except, int $tv_sec, int $tv_usec = null): int`

对流的给定数组运行 `select()` 系统调用。对应 `stream_select()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$read` | `array` | 查看是否有字符可读的流数组 |
| `$write` | `array` | 查看是否有字符可写的流数组 |
| `$except` | `array` | 查看是否可导出的流数组 |
| `$tv_sec` | `int` | 超时秒数 |
| `$tv_usec` | `int\|null` | 超时微秒数 |

- 返回变化的流数量，失败时抛出 `RuntimeException`。

---

## 继承的方法

从 [FileF](FileF.md) 继承：`open()`、`close()`、`getcsv()`、`putcsv()`。

从 [FileAbstract](FileAbstract.md) 间接继承：`eof()`、`flush()`、`getc()`、`gets()`、`getss()`、`lock()`、`passthru()`、`puts()`、`read()`、`scanf()`、`seek()`、`tell()`、`truncate()`、`write()`、`rewind()`、`setBuffer()`。
