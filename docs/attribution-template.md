# Source Attribution Template

When extracting code from the Telegraph project, use this simple header format:

```php
<?php declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/src/[ORIGINAL_PATH]
 * Date: [YYYY-MM-DD]
 */
```

## Template Variables

- `[ORIGINAL_PATH]`: The relative path to the original file in the Telegraph project (e.g., `DTO/User.php`, `Enums/ChatActions.php`)
- `[YYYY-MM-DD]`: The date when the file was extracted and adapted (e.g., `2025-11-06`)

## Examples

```php
/**
 * Extracted from: vendor_sources/telegraph/src/DTO/User.php
 * Date: 2025-11-06
 */
```

```php
/**
 * Extracted from: vendor_sources/telegraph/src/Enums/ChatActions.php
 * Date: 2025-11-06
 */
```

Simple and consistent - just the reference and date for traceability.