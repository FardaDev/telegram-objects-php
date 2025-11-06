---
inclusion: always
---

### Test Driven Design

- **Write tests first** for any new code or feature.  
- Implement the code to make the tests pass.  
- **Run the tests** immediately after writing the code.  
- If tests fail, **fix the code** until all tests pass.  
- Ensure tests cover **expected behavior, edge cases, and error conditions**.
- When a task is done do not forget to mark it as complete in tasks.md in specs

### Testing Workflow

Use the predefined composer scripts for consistent testing:

#### Quick Testing Commands
- `composer test:unit` - Run unit tests with Pest (fast feedback)
- `composer test:lint` - Check code style without fixing (dry-run)
- `composer test:types` - Run PHPStan static analysis
- `composer test` - Run full test suite (lint + types + unit tests)

#### Development Commands
- `composer lint` - Fix code style issues automatically
- `composer coverage` - Run tests with coverage report

#### Testing Best Practices
- **Always run `composer test:unit` after writing/modifying code**
- **Run `composer test:types` to catch type errors early**
- **Use `composer test` before committing to ensure full compliance**
- **Write descriptive test names** that explain the behavior being tested
- **Test both success and failure scenarios** for each method
- **Include edge cases** like empty arrays, null values, invalid data
- **Follow the existing test patterns** in the codebase for consistency

#### Test Structure Guidelines
- Use **Pest** testing framework with descriptive `it()` blocks
- Test file naming: `{ClassName}Test.php` in `tests/Unit/DTO/`
- Test methods should cover:
  - Creation from array with minimal fields
  - Creation from array with all fields
  - Array conversion (`toArray()`)
  - Null value filtering in `toArray()`
  - Validation exceptions for required fields
  - Helper methods and business logic
- Use `expect()` assertions for clear, readable tests
- Group related tests logically within the file

#### Code Quality Standards
- **PHPStan Level 8** compliance required
- **PHP-CS-Fixer** for consistent code style
- **100% test pass rate** before merging
- **Meaningful commit messages** following conventional commits format