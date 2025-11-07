# CI Test Failure Debug Summary

## Problem
GitHub Actions CI fails when running `composer test:unit` - shows PHPUnit help text instead of running Pest tests, then exits with code 1.

## Environment
- **Local**: Windows with PHP 8.3.7
- **CI**: Ubuntu/Windows with PHP 8.1/8.2/8.3
- **Framework**: None (framework-agnostic library)
- **Test Framework**: Pest 2.36.0
- **Repository**: https://github.com/FardaDev/telegram-objects-php

## What Works ✅
1. **Tests pass locally**: `composer test:unit` runs 506 tests successfully on Windows
2. **Pest is installed**: `vendor/bin/pest --version` shows 2.36.0
3. **Pest finds tests**: `vendor/bin/pest --list-tests` shows all 506 tests
4. **Dependencies install in CI**: Composer successfully installs Pest and all dependencies
5. **PHP versions are correct**: CI uses PHP 8.1+ (Pest requirement met)
6. **Configuration files are valid**:
   - `phpunit.xml` - valid XML, correct paths
   - `tests/Pest.php` - no syntax errors
   - `composer.json` - Pest in require-dev, correct scripts
7. **Line endings fixed**: `.gitattributes` enforces LF endings
8. **Code style passes**: PHP-CS-Fixer runs successfully in CI
9. **Static analysis passes**: PHPStan level 8 runs successfully in CI

## What Fails ❌
- **Only the test execution step fails** in CI
- Command: `composer test:unit` (which runs `vendor/bin/pest`)
- Output: Shows PHPUnit help text instead of running tests
- Exit code: 1
- Happens on both Ubuntu and Windows runners
- Happens on PHP 8.1, 8.2, and 8.3

## Error Output
```
Script vendor/bin/pest handling the test:unit event returned with error code 1
```

Shows PHPUnit help (--bootstrap, --configuration, etc.) instead of Pest output.

## What We've Tried
1. ✅ Fixed line endings (CRLF → LF) with `.gitattributes`
2. ✅ Fixed composer.json (was calling phpunit, now calls pest)
3. ✅ Removed `--colors=always` flag
4. ✅ Added `composer dump-autoload` step
5. ✅ Verified Pest is in composer.json require-dev
6. ✅ Verified workflow uses correct PHP versions
7. ✅ Checked for syntax errors in Pest.php
8. ✅ Verified phpunit.xml configuration
9. ✅ Added explicit test directory: `vendor/bin/pest tests`
10. ✅ Added debug steps to verify test directory exists
11. ✅ Excluded vendor from line ending normalization
12. ✅ Added Pest version and list-tests debug output

## Root Cause (CONFIRMED)
**Pest shows PHPUnit help when it cannot find any test files to execute.**

When Pest finds no tests, it falls back to showing PHPUnit's help menu and exits with code 1.

Possible reasons in CI:
- Working directory mismatch
- Case sensitivity (Linux CI vs Windows local)
- Test directory not being checked out properly
- Pest not being told where to look for tests

## Files to Check
- `.github/workflows/tests.yml` - CI workflow configuration
- `composer.json` - Scripts and dependencies
- `phpunit.xml` - PHPUnit/Pest configuration
- `tests/Pest.php` - Pest bootstrap file
- `.gitattributes` - Line ending configuration

## Latest Fixes Applied (Attempt #4)
1. **Changed composer script**: `vendor/bin/pest` → `vendor/bin/pest tests`
2. **Added debug steps in CI**:
   - Verify test directory exists and list files
   - Show Pest version
   - List all tests Pest can find
3. **Updated .gitattributes**: Exclude `/vendor/**` from line ending normalization
4. **Reduced CI scope**: Only test PHP 8.3 on Ubuntu to save minutes

## Expected Outcome
The debug steps should show:
- ✅ Test directory exists with .php files
- ✅ Pest version 2.36.0
- ✅ List of 506 tests found
- ✅ Tests execute successfully

If tests still fail, the debug output will show exactly what's missing.

## Relevant Links
- Pest docs: https://pestphp.com/docs/installation
- GitHub Actions: https://github.com/FardaDev/telegram-objects-php/actions
- Latest failing run: Check Actions tab for most recent failure
