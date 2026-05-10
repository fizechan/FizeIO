# FizeIO 参考手册

FizeIO 是对 I/O（输入输出对象）的功能性类库，实现文件夹、文件、缓冲区、流、上传等 IO 类管理操作功能。

## 类架构总览

```
FileAbstract (抽象类) — 封装 PHP 文件资源，提供 f*() 函数的 OOP 包装
├── FileF — 原生文件句柄（fopen/fclose），增加 CSV 支持
│   └── Stream — 扩展 FileF，增加流专用操作
└── FileP — 进程文件句柄（popen/pclose）

File — 继承 SplFileObject，提供文件系统级操作（chmod、copy、rename 等）

Directory — 目录操作（扫描、创建、删除、遍历）
└── Disk — 扩展 Directory，增加磁盘空间查询

Upload — HTTP 文件上传处理（验证、保存）
OB — 输出缓冲区控制
Output — 输出 URL 重写控制
MIME — MIME 类型与后缀名映射
Extension — 文件后缀名工具
```

## 章节导航

- [文件 File](File.md) — 基于 SplFileObject 的文件操作类
- [文件基类 FileAbstract](FileAbstract.md) — 基于 PHP 资源的文件流抽象基类
- [原生文件 FileF](FileF.md) — 基于 fopen 的文件操作类
- [进程文件 FileP](FileP.md) — 基于 popen 的进程文件操作类
- [流 Stream](Stream.md) — 流处理操作类
- [目录 Directory](Directory.md) — 目录操作类
- [磁盘 Disk](Disk.md) — 磁盘空间查询类
- [上传 Upload](Upload.md) — 文件上传处理类
- [输出缓冲 OB](OB.md) — 输出缓冲区控制类
- [输出 Output](Output.md) — 输出 URL 重写控制类
- [MIME 类型 MIME](MIME.md) — MIME 类型与扩展名映射类
- [扩展名 Extension](Extension.md) — 文件扩展名工具类
