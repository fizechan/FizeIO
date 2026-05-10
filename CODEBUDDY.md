# CODEBUDDY.md

This file provides guidance to CodeBuddy Code when working with code in this repository.

## Project Overview

FizeIO is a PHP I/O utility library (`fize/io`, v9.2.0) under namespace `Fize\IO`. It provides OOP wrappers for file, directory, stream, upload, and output buffering operations. Requires PHP >= 7.2.0.

## Common Commands

```bash
# Install dependencies
composer install

# Run all tests
vendor/bin/phpunit tests/

# Run a single test file
vendor/bin/phpunit tests/TestFile.php

# Run a specific test method
vendor/bin/phpunit tests/TestFile.php --filter testExists

# Run tests for a specific class
vendor/bin/phpunit tests/TestStream.php
```

No `phpunit.xml` exists; PHPUnit must be pointed at the `tests/` directory or individual test files explicitly.

## Architecture

### Class Hierarchy

```
FileAbstract (abstract) — wraps PHP file resources, provides f*() OOP wrappers
├── FileF — native file handle (fopen/fclose), adds CSV support
│   └── Stream — extends FileF, adds stream-specific ops (copyToStream, getMetaData, setBlocking, etc.)
└── FileP — process file handle (popen/pclose), for reading/writing to process pipes

File — extends SplFileObject (NOT FileAbstract), provides filesystem-level ops (chmod, copy, rename, delete, getMime, split)

Directory (standalone) — directory handle with scan, create, delete, and static exists/realpath
└── Disk — extends Directory, adds freeSpace/totalSpace

Upload — HTTP file upload handler with validation (size, MIME, extension) and save
OB — static output buffering wrappers
Output — static URL rewrite var helpers
MIME — static MIME↔extension mapping with ~780 entries and internal caching
Extension — static helper (isImage)
```

### Key Design Decisions

- **Two parallel file class families**: `File` (SplFileObject-based) for filesystem operations, and `FileAbstract` hierarchy (resource-based) for stream-level I/O. They are independent — `File` does not extend `FileAbstract`.
- **Auto-directory creation**: `File::__construct()`, `FileF::open()`, and `Directory::__construct()` all auto-create parent directories in write modes.
- **Windows case-sensitivity**: `File::exists()` and `Directory::exists()` enforce case-sensitive path checks on Windows (native FS is case-insensitive).
- **Exception-based errors**: FileAbstract hierarchy methods throw `RuntimeException` when underlying PHP functions return `false`.
- **Destructor cleanup**: `FileF`, `FileP`, and `Directory` close their handles in destructors to prevent leaks.
- **MIME caching**: The `MIME` class uses static property caches to avoid repeated lookups.

### Test Conventions

- Test class naming: `Test{ClassName}` in the `Tests` namespace (e.g., `TestFile`, `TestFileF`)
- Test method naming: `test{MethodName}` (e.g., `testOpen`, `test__construct`)
- All tests extend `PHPUnit\Framework\TestCase`
- `FileAbstract` is tested indirectly via `FileF` and `FileP` (it is abstract)
- Test data lives in `temp/` at the project root
- Some tests are `@todo` (not yet passing), e.g., `testChgrp()` and `testChown()`
- `TestFileP` requires OS-aware commands (Windows vs Linux)

### Suggested PHP Extensions

- `ext-exif` — for EXIF image metadata
- `ext-fileinfo` — for MIME type detection
- `ext-zlib` — for compressed stream support
