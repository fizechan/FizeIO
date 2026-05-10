# 进程文件 FileP

> 命名空间：`Fize\IO`
> 源文件：`src/FileP.php`
> 父类：[FileAbstract](FileAbstract.md)

`FileP` 继承自 [FileAbstract](FileAbstract.md)，基于 `popen()`/`pclose()` 实现进程文件的打开与关闭。适用于需要通过文件句柄与进程管道交互的场景。

---

## 构造与析构

### `__construct($stream = null)`

继承自 [FileAbstract](FileAbstract.md)。可传入已有的文件流资源。

### `__destruct()`

析构时自动通过 `pclose()` 关闭进程文件流，防止资源泄漏。

---

## 实例方法

### `open(string $command, string $mode)`

打开进程文件。对应 `popen()`。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$command` | `string` | 要执行的命令 |
| `$mode` | `string` | 模式（`'r'` 读 / `'w'` 写） |

- 若当前已有未关闭的流，抛出 `RuntimeException`。

---

### `close(): int`

关闭进程文件。对应 `pclose()`。

- 返回终止状态码（int）。错误时返回 `-1`。
- 成功关闭后清空内部流资源引用。
- 如果流已不是有效资源，直接返回 `-1`。

> 与 [FileF::close()](FileF.md) 不同，`FileP::close()` 返回 `int`（进程终止状态），而非 `bool`。

---

## 继承的方法

从 [FileAbstract](FileAbstract.md) 继承的所有方法均可使用：`eof()`、`flush()`、`getc()`、`gets()`、`getss()`、`lock()`、`passthru()`、`puts()`、`read()`、`scanf()`、`seek()`、`tell()`、`truncate()`、`write()`、`rewind()`、`setBuffer()`。

> 注意：进程管道是单向的，`seek()`、`tell()` 等需要双向流的方法在进程管道上可能不可用。
