# 磁盘 Disk

> 命名空间：`Fize\IO`
> 源文件：`src/Disk.php`
> 父类：[Directory](Directory.md)

`Disk` 继承自 [Directory](Directory.md)，在目录操作基础上增加磁盘空间查询功能。

---

## 构造方法

### `__construct(string $directory)`

| 参数 | 类型 | 说明 |
|---|---|---|
| `$directory` | `string` | 指定目录或盘符 |

- 调用父类 `Directory::__construct()` 进行初始化。

---

## 实例方法

### `freeSpace(): float`

返回可用空间。对应 `disk_free_space()`。

- 返回可用字节数（float），失败时抛出 `RuntimeException`。

---

### `totalSpace(): float`

返回总大小。对应 `disk_total_space()`。

- 返回总字节数（float），失败时抛出 `RuntimeException`。

---

## 继承的方法

从 [Directory](Directory.md) 继承：`open()`、`close()`、`read()`、`rewind()`、`clear()`、`scan()`、`tempnam()`、`create()`、`delete()`、`exists()`、`realpath()`。
