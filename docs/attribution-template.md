# Attribution Template

## Required Header Format

All extracted files MUST use this exact format with real values (no placeholders):

```php
<?php declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/src/[exact_path_to_source_file]
 * Telegraph commit: 0f4a6cf4
 * Date: YYYY-MM-DD (use actual current date when creating the file)
 */
```

## Current Telegraph Information

- **Repository**: https://github.com/defstudio/telegraph
- **Current commit**: Get from `upstream.json` -> `last_commit_short`
- **Full commit hash**: Get from `upstream.json` -> `last_commit`
- **Always use the commit from upstream.json, not hardcoded values**

## Examples

### DTO File
```php
/**
 * Extracted from: vendor_sources/telegraph/src/DTO/User.php
 * Telegraph commit: [get from upstream.json]
 * Date: 2025-11-07
 */
```

### Exception File
```php
/**
 * Extracted from: vendor_sources/telegraph/src/Exceptions/TelegraphException.php
 * Telegraph commit: [get from upstream.json]
 * Date: 2025-11-07
 */
```

### Enum File
```php
/**
 * Extracted from: vendor_sources/telegraph/src/Enums/ChatActions.php
 * Telegraph commit: [get from upstream.json]
 * Date: 2025-11-07
 */
```

## What NOT to Use

❌ **Never use placeholders:**
- `[commit_hash]`
- `[date]`
- `[filename]`
- `[path]`

❌ **Never use generic descriptions:**
- "This file is part of the Telegram Objects PHP library"
- "Adapted from DefStudio/Telegraph"
- Generic license headers without extraction info

## Verification

To verify the current Telegraph commit:
```bash
git -C vendor_sources/telegraph log -1 --format="%h %s"
```