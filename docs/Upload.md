# 上传 Upload

> 命名空间：`Fize\IO`
> 源文件：`src/Upload.php`

`Upload` 类用于处理 HTTP 文件上传，支持文件大小验证、MIME 类型验证、后缀名验证、图像合法性检测，以及灵活的保存命名规则。

---

## 构造方法

### `__construct($file, array $config = [])`

| 参数 | 类型 | 说明 |
|---|---|---|
| `$file` | `string\|array` | `$_FILES` 中的键名或 `$_FILES` 数组项 |
| `$config` | `array` | 配置项 |

**配置项说明：**

| 配置键 | 类型 | 默认值 | 说明 |
|---|---|---|---|
| `size` | `int` | `2 * 1024 * 1024` | 单个上传文件的最大字节数 |
| `ext` | `string\|array\|null` | `null` | 允许的文件后缀，多个用逗号分割或数组 |
| `type` | `string\|array\|null` | `null` | 允许的文件 MIME 类型，多个用逗号分割或数组 |
| `rule` | `string\|Closure` | `'date'` | 上传文件保存规则 |
| `dir` | `string` | `'./upload'` | 上传文件保存目录 |
| `name` | `bool\|string` | `true` | 保存的文件名。`true`：自动生成；`false`/`''`：保留原文件名 |
| `replace` | `bool` | `true` | 同名文件是否覆盖 |
| `autoext` | `bool` | `true` | 自动补充扩展名 |

> 传入字符串时，从 `$_FILES` 中获取对应的上传文件信息。如果键不存在，抛出 `OutOfBoundsException`。

---

## 实例方法

### `path(): string`

返回保存文件的完整路径（反斜杠统一替换为正斜杠）。

---

### `save(): File`

保存上传文件，返回 [File](File.md) 实例。

- 执行前会自动进行验证（合法性、大小、MIME、后缀、图像检测）。
- 如果上传存在错误（`$_FILES['error']` 不为 0），抛出 `RuntimeException`。
- 如果同名文件已存在且不允许覆盖，抛出 `RuntimeException`。
- 如果文件移动失败，抛出 `RuntimeException`。

---

## 静态方法

### `config(array $config = [])`

设置全局默认配置（单例配置），后续通过 `single()` 或 `multiple()` 调用时会合并此配置。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$config` | `array` | 配置项 |

---

### `single($file, array $config = []): array`

简易模式下的单文件上传。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$file` | `string\|array` | `$_FILES` 键名或数组 |
| `$config` | `array` | 配置项 |

**返回值：**

```php
[
    'file' => File,   // File 实例
    'path' => string   // 保存路径
]
```

---

### `multiple($files, array $config = []): array`

简易模式下的多文件上传。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$files` | `string\|array` | `$_FILES` 键名、键名数组或符合 `$_FILES` 格式的数组 |
| `$config` | `array` | 配置项 |

**返回值：** 由 `single()` 返回值组成的数组。

---

## 保存命名规则

`config['rule']` 支持以下值：

| 规则值 | 说明 |
|---|---|
| `'date'` | 默认规则，按日期分目录 + MD5 命名：`Ymd/md5(microtime)` |
| 哈希算法名（如 `'md5'`、`'sha256'`） | 取文件哈希前2位作子目录，剩余部分作文件名 |
| `Closure` | 传入回调函数，参数为上传文件信息数组 |
| 其他 `callable` | 调用该函数生成文件名 |
| 其他值 | 回退到 `'date'` 规则 |

---

## 上传错误码

| 错误码 | 说明 |
|---|---|
| `1` / `2` | 文件大小超出最大值 |
| `3` | 文件只有部分被上传 |
| `4` | 没有文件被上传 |
| `6` | 找不到临时目录 |
| `7` | 文件写入失败 |
