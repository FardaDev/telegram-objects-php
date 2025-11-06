# Source Attribution Template

When extracting code from the Telegraph project, use this header format:

```php
<?php declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/src/[ORIGINAL_PATH]
 * Telegraph commit: [COMMIT_HASH]
 * Date: [YYYY-MM-DD]
 */
```

## Template Variables

- `[ORIGINAL_PATH]`: The relative path to the original file in the Telegraph project (e.g., `DTO/User.php`, `Enums/ChatActions.php`)
- `[COMMIT_HASH]`: The Git commit hash from Telegraph when the file was extracted (first 8 characters)
- `[YYYY-MM-DD]`: The date when the file was extracted and adapted (e.g., `2025-11-06`)

## Current Telegraph Version

**Current commit**: `0f4a6cf4` (as of 2025-11-06)

## Examples

```php
/**
 * Extracted from: vendor_sources/telegraph/src/DTO/User.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-06
 */
```

```php
/**
 * Extracted from: vendor_sources/telegraph/src/Enums/ChatActions.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-06
 */
```

## Why Include Commit Hash?

- **Version tracking**: Know exactly which Telegraph version the code came from
- **Update detection**: Compare with current Telegraph commit to see if updates are needed
- **Diff analysis**: Can generate diffs between our version and current Telegraph version
- **Maintenance**: Makes it easy to sync with upstream changes