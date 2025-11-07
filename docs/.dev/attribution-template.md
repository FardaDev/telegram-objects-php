# Attribution Template

Standard header formats for all PHP files in telegram-objects-php.

## Template 1: Telegraph-Inspired Files

For files adapted from DefStudio/Telegraph (DTOs, Enums, Keyboards, Exceptions, Tests):

```php
<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: src/DTO/User.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-06
 */
```

**Fields:**
- `Inspired by:` - Always "defstudio/telegraph" with GitHub URL
- `Original file:` - Path in Telegraph repo (relative to root)
- `Telegraph commit:` - Short hash from `upstream.json` -> `last_commit_short`
- `Adapted:` - Date created/adapted (YYYY-MM-DD)

## Template 2: Created for telegram-objects-php

For library-specific files (Support classes, Contracts, new Exceptions, custom Tests):

```php
<?php

declare(strict_types=1);

/**
 * Created for: telegram-objects-php (https://github.com/FardaDev/telegram-objects-php)
 * Purpose: Lightweight collection to replace Laravel's Collection dependency
 * Created: 2025-11-06
 */
```

**Fields:**
- `Created for:` - Always "telegram-objects-php" with GitHub URL
- `Purpose:` - Brief description (1-2 lines)
- `Created:` - Date created (YYYY-MM-DD)

## Rules

**Always include:**
- Full GitHub URLs
- Commit hashes for Telegraph files
- Purpose for created files
- ISO 8601 dates (YYYY-MM-DD)

**Never use:**
- Placeholders like `[commit_hash]`, `[date]`
- Generic descriptions without attribution

**When to update:**
- Never change original dates
- Update commit hash only when syncing upstream
- Document changes in git commits, not headers

## Verification

Check current Telegraph commit:
```bash
cat upstream.json
# or
git -C vendor_sources/telegraph log -1 --format="%h %s"
```