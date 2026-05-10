# MIME 类型 MIME

> 命名空间：`Fize\IO`
> 源文件：`src/MIME.php`

`MIME` 类提供 MIME 类型与文件后缀名之间的双向映射功能，内置约 780 条常见 MIME 映射记录。所有方法均为静态方法，无需实例化。内部使用静态属性缓存以提升查询性能。

> 数据来源：[Apache httpd mime.types](http://svn.apache.org/repos/asf/httpd/httpd/branches/1.3.x/conf/mime.types)

---

## 静态方法

### `getExtensionByMime(string $mime, bool $onlyFirst = false): ?string`

返回 MIME 类型对应的后缀名。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$mime` | `string` | MIME 类型 |
| `$onlyFirst` | `bool` | 如果对应多个后缀名，是否只返回第一个 |

- 若 MIME 对应多个后缀名且 `$onlyFirst = false`，返回逗号分隔的字符串（如 `"jpg,jpeg,jpe"`）。
- 未找到时返回 `null`。

**示例：**

```php
MIME::getExtensionByMime('image/jpeg');         // 'jpg,jpeg,jpe'
MIME::getExtensionByMime('image/jpeg', true);  // 'jpg'
MIME::getExtensionByMime('application/zip');    // 'zip'
MIME::getExtensionByMime('unknown/type');       // null
```

---

### `getMimeByExtension(string $extension): ?string`

根据后缀名返回对应的 MIME 类型。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$extension` | `string` | 文件后缀名（不含点号） |

- 内部使用缓存优化重复查询。
- 未找到时返回 `null`。

**示例：**

```php
MIME::getMimeByExtension('zip');   // 'application/zip'
MIME::getMimeByExtension('jpg');   // 'image/jpeg'
MIME::getMimeByExtension('txt');   // 'text/plain'
MIME::getMimeByExtension('abc');   // null
```

---

### `hasMultipleExtensions(string $mime): ?bool`

判断 MIME 类型是否对应多个后缀名。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$mime` | `string` | MIME 类型 |

- 对应多个后缀名返回 `true`，单个返回 `false`。
- 未找到时返回 `null`。

---

## 缓存机制

`MIME` 类使用以下静态属性进行内部缓存，避免重复计算：

| 缓存属性 | 用途 |
|---|---|
| `$cacheHasMimesHasMultipleExtensions` | 缓存 `hasMultipleExtensions()` 的结果 |
| `$cacheGetMimesHasMultipleExtensions` | 缓存多后缀 MIME 映射表 |
| `$cacheGetExtensionsMimes` | 缓存后缀→MIME 反转映射表 |
| `$cacheGetMimeByExtension` | 缓存 `getMimeByExtension()` 的结果 |

缓存会在同一请求内持续生效，不同请求间不持久化。
