---
inclusion: always
---

### Test Driven Design

- **Write tests first** for any new code or feature
- **Run tests** immediately after writing code: `composer test:unit`
- **Fix code** until all tests pass
- **Mark tasks complete** in tasks.md when done

### Essential Commands

- `composer test:unit` - Run tests (fast feedback)
- `composer test` - Full check before commit (lint + types + tests)
- `composer lint` - Fix code style issues

### Code Quality

- Use **Pest** with descriptive `it()` blocks
- Test both success and error cases
- Follow existing test patterns
- PHPStan level 8 compliance required