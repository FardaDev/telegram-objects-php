# Attribution Template

This document defines the standard header formats for all PHP files in the telegram-objects-php library.

## Two Header Templates

We use two distinct header templates depending on the file's origin:

### Template 1: Telegraph-Inspired Files

For files adapted from the DefStudio/Telegraph project:

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

**Use this template for:**
- All DTO classes adapted from Telegraph
- Enum classes from Telegraph
- Keyboard classes from Telegraph
- Exception classes from Telegraph
- Contracts from Telegraph
- Test files ported from Telegraph

**Fields:**
- `Inspired by:` - Always "defstudio/telegraph" with GitHub URL
- `Original file:` - Path to the original file in Telegraph repo (relative to repo root)
- `Telegraph commit:` - Short commit hash from `upstream.json` -> `last_commit_short`
- `Adapted:` - Date when the file was created/adapted (YYYY-MM-DD format)

### Template 2: Created for telegram-objects-php

For files created specifically for this library:

```php
<?php

declare(strict_types=1);

/**
 * Created for: telegram-objects-php (https://github.com/FardaDev/telegram-objects-php)
 * Purpose: Lightweight collection implementation to replace Laravel's Collection dependency
 * Created: 2025-11-06
 */
```

**Use this template for:**
- Support classes (Collection, Validator, TelegramDateTime)
- Framework-agnostic contracts (ArrayableInterface, SerializableInterface)
- Enhanced/new exception classes (ValidationException, SerializationException, PaymentException)
- Test files for created classes
- Test files that don't exist in Telegraph

**Fields:**
- `Created for:` - Always "telegram-objects-php" with GitHub URL
- `Purpose:` - Brief description of why this file was created (1-2 lines)
- `Created:` - Date when the file was created (YYYY-MM-DD format)

## Examples

### Example 1: DTO Class (Telegraph-Inspired)
```php
<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: src/DTO/Message.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-06
 */

namespace Telegram\Objects\DTO;

class Message implements ArrayableInterface
{
    // ...
}
```

### Example 2: Support Class (Created)
```php
<?php

declare(strict_types=1);

/**
 * Created for: telegram-objects-php (https://github.com/FardaDev/telegram-objects-php)
 * Purpose: Centralized validation utility for consistent DTO data validation
 * Created: 2025-11-06
 */

namespace Telegram\Objects\Support;

class Validator
{
    // ...
}
```

### Example 3: Test File (Telegraph-Inspired)
```php
<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: tests/Unit/DTO/UserTest.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-07
 */

use Telegram\Objects\DTO\User;

it('can create user from array', function () {
    // ...
});
```

### Example 4: Test File (Created)
```php
<?php

declare(strict_types=1);

/**
 * Created for: telegram-objects-php (https://github.com/FardaDev/telegram-objects-php)
 * Purpose: Test suite for Button class - no equivalent exists in Telegraph source
 * Created: 2025-11-07
 */

namespace Telegram\Objects\Tests\Unit\Keyboard;

use PHPUnit\Framework\TestCase;

class ButtonTest extends TestCase
{
    // ...
}
```

## Current Telegraph Information

- **Repository**: https://github.com/defstudio/telegraph
- **Current commit**: Get from `upstream.json` -> `last_commit_short`
- **Full commit hash**: Get from `upstream.json` -> `last_commit`
- **Always use the commit from upstream.json, not hardcoded values**

## Verification Command

To verify the current Telegraph commit:
```bash
git -C vendor_sources/telegraph log -1 --format="%h %s"
```

Or check the `upstream.json` file:
```bash
cat upstream.json
```

## What NOT to Use

❌ **Never use these old formats:**
- "Extracted from:" (old format, replaced by "Inspired by:")
- Generic descriptions without proper attribution
- Placeholders like `[commit_hash]`, `[date]`, `[filename]`

❌ **Never omit:**
- GitHub repository URLs
- Commit hashes for Telegraph-inspired files
- Purpose descriptions for created files
- Proper date formatting (YYYY-MM-DD)

## Consistency Rules

1. **Always include the full GitHub URL** in the first line
2. **Use consistent field names** as shown in templates
3. **Keep dates in YYYY-MM-DD format** (ISO 8601)
4. **Use short commit hashes** (7-8 characters) from upstream.json
5. **Write clear, concise purpose statements** for created files
6. **Maintain proper spacing** and formatting as shown in examples

## When to Update Headers

- **Never change** the original adaptation/creation date
- **Update commit hash** only when syncing with upstream Telegraph changes
- **Keep headers stable** once files are created
- **Document changes** in git commit messages, not in headers