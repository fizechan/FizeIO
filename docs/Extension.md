# 扩展名 Extension

> 命名空间：`Fize\IO`
> 源文件：`src/Extension.php`

`Extension` 类提供文件后缀名相关的工具方法，所有方法均为静态方法，无需实例化。

---

## 静态方法

### `isImage(string $ext): bool`

根据后缀名判断是否是图片类型。

| 参数 | 类型 | 说明 |
|---|---|---|
| `$ext` | `string` | 文件后缀名（不含点号，不区分大小写） |

**支持的图片后缀名：**

`bmp`、`gif`、`ico`、`iff`、`jb2`、`jp2`、`jpc`、`jpeg`、`jpg`、`jph`、`jpx`、`png`、`psd`、`svg`、`swc`、`swf`、`tiff`、`wbmp`、`webp`、`xbm`

**示例：**

```php
Extension::isImage('jpg');    // true
Extension::isImage('PNG');    // true（不区分大小写）
Extension::isImage('pdf');    // false
```
