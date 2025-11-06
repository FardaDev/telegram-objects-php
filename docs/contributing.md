# Contributing to Telegram Objects PHP

Thank you for your interest in contributing to the Telegram Objects PHP library! This document provides guidelines and information for contributors.

## Table of Contents

- [Getting Started](#getting-started)
- [Development Setup](#development-setup)
- [Code Standards](#code-standards)
- [Testing](#testing)
- [Submitting Changes](#submitting-changes)
- [Upstream Synchronization](#upstream-synchronization)
- [Release Process](#release-process)

## Getting Started

### Prerequisites

- PHP 8.1 or higher
- Composer
- Git
- Python 3.7+ (for upstream tracking scripts)

### Development Setup

1. **Fork and Clone**
   ```bash
   git clone https://github.com/YOUR_USERNAME/telegram-objects-php.git
   cd telegram-objects-php
   ```

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Install Python Dependencies** (for upstream tracking)
   ```bash
   composer run upstream:install
   ```

4. **Verify Setup**
   ```bash
   composer test
   composer run upstream:status
   ```

## Code Standards

### PHP Standards

We follow strict coding standards to maintain code quality:

#### PSR Standards
- **PSR-4** for autoloading
- **PSR-12** for coding style
- **PHPStan Level 8** for static analysis

#### Type Safety
- Use strict typing: `declare(strict_types=1);`
- Always declare parameter and return types
- Use union types (`string|null`) over mixed types
- Prefer `readonly` properties for immutable data

#### Code Style
```php
<?php

declare(strict_types=1);

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Support\Validator;

/**
 * Represents a Telegram user.
 */
final class User implements ArrayableInterface
{
    public function __construct(
        public readonly int $id,
        public readonly string $firstName,
        public readonly ?string $lastName = null,
        public readonly ?string $username = null,
        public readonly bool $isBot = false,
    ) {
    }

    /**
     * Create from array data.
     *
     * @param array<string, mixed> $data
     * @throws \Telegram\Objects\Exceptions\ValidationException
     */
    public static function fromArray(array $data): self
    {
        Validator::requireField($data, 'id', 'User');
        Validator::requireField($data, 'first_name', 'User');

        return new self(
            id: Validator::getValue($data, 'id', null, 'int'),
            firstName: Validator::getValue($data, 'first_name', null, 'string'),
            lastName: Validator::getValue($data, 'last_name', null, 'string'),
            username: Validator::getValue($data, 'username', null, 'string'),
            isBot: Validator::getValue($data, 'is_bot', false, 'bool'),
        );
    }

    /**
     * Convert to array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'username' => $this->username,
            'is_bot' => $this->isBot,
        ], fn ($value) => $value !== null);
    }
}
```

### Validation Standards

All DTO classes must use consistent validation:

```php
// ✅ Correct - Use Validator class
Validator::requireField($data, 'field_name', 'ContextClass');
$value = Validator::getValue($data, 'field_name', $default, 'expected_type');

// ❌ Incorrect - Manual validation
if (!isset($data['field_name'])) {
    throw new ValidationException('Missing field');
}
```

### Attribution Standards

All extracted files must include proper attribution:

```php
/**
 * Extracted from: vendor_sources/telegraph/src/DTO/User.php
 * Telegraph commit: abc123456789
 * Date: 2025-11-07
 */
```

For new files not based on Telegraph:

```php
/**
 * Created for: telegram-objects-php library
 * Telegraph commit: abc123456789
 * Date: 2025-11-07
 * 
 * Brief description of the file's purpose.
 */
```

## Testing

### Test Requirements

- All new functionality must include tests
- Maintain or improve test coverage
- Tests must pass on PHP 8.1+
- Follow existing test patterns

### Running Tests

```bash
# Full test suite
composer test

# Individual test types
composer test:unit      # PHPUnit/Pest tests
composer test:types     # PHPStan static analysis
composer test:lint      # Code style checks

# Coverage report
composer coverage

# Upstream tracking tests
composer run upstream:test
```

### Test Structure

```php
<?php

declare(strict_types=1);

namespace Telegram\Objects\Tests\Unit\DTO;

use PHPUnit\Framework\TestCase;
use Telegram\Objects\DTO\User;
use Telegram\Objects\Exceptions\ValidationException;

final class UserTest extends TestCase
{
    public function test_it_can_be_created_with_required_fields(): void
    {
        $user = new User(123, 'John');
        
        $this->assertSame(123, $user->id);
        $this->assertSame('John', $user->firstName);
        $this->assertNull($user->lastName);
        $this->assertFalse($user->isBot);
    }

    public function test_it_can_be_created_from_array(): void
    {
        $data = [
            'id' => 123,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'johndoe',
            'is_bot' => false,
        ];

        $user = User::fromArray($data);

        $this->assertSame(123, $user->id);
        $this->assertSame('John', $user->firstName);
        $this->assertSame('Doe', $user->lastName);
        $this->assertSame('johndoe', $user->username);
        $this->assertFalse($user->isBot);
    }

    public function test_it_throws_exception_for_missing_required_field(): void
    {
        $this->expectException(ValidationException::class);
        
        User::fromArray(['first_name' => 'John']); // Missing 'id'
    }

    public function test_it_converts_to_array(): void
    {
        $user = new User(123, 'John', 'Doe', 'johndoe', false);
        
        $expected = [
            'id' => 123,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'johndoe',
            'is_bot' => false,
        ];

        $this->assertSame($expected, $user->toArray());
    }
}
```

## Submitting Changes

### Pull Request Process

1. **Create a Branch**
   ```bash
   git checkout -b feature/your-feature-name
   ```

2. **Make Changes**
   - Follow code standards
   - Add tests for new functionality
   - Update documentation if needed

3. **Test Your Changes**
   ```bash
   composer test
   ```

4. **Commit Changes**
   Use [Conventional Commits](https://conventionalcommits.org/) format:
   ```bash
   git commit -m "feat: add new DTO for Telegram feature"
   git commit -m "fix: resolve validation issue in User DTO"
   git commit -m "docs: update README with new examples"
   ```

5. **Push and Create PR**
   ```bash
   git push origin feature/your-feature-name
   ```

### Commit Message Format

```
<type>: <description>

[optional body]

[optional footer]
```

**Types:**
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `test`: Adding or updating tests
- `refactor`: Code restructuring
- `chore`: Maintenance tasks

### PR Requirements

- [ ] All tests pass
- [ ] Code follows style guidelines
- [ ] New functionality includes tests
- [ ] Documentation updated if needed
- [ ] Attribution headers are correct
- [ ] No breaking changes (or clearly documented)

## Upstream Synchronization

This library tracks the upstream [DefStudio/Telegraph](https://github.com/defstudio/telegraph) repository.

### Checking for Updates

```bash
# Check for upstream changes
composer run upstream:check

# View current status
composer run upstream:status
```

### Applying Upstream Changes

When upstream changes are detected:

1. **Review Changes**
   - Examine the generated diff report
   - Identify affected files and functionality

2. **Apply Changes**
   - Update corresponding files in our library
   - Maintain framework-agnostic approach
   - Update attribution headers

3. **Test Changes**
   ```bash
   composer test
   ```

4. **Update Tracking**
   ```bash
   python scripts/check-upstream.py --update NEW_COMMIT_HASH
   ```

For detailed information, see [upstream-sync.md](upstream-sync.md).

### File Categories

When working with upstream changes, understand our file structure:

- **DTO Classes** (45 files): `src/DTO/` - Core Telegram API objects
- **DTO Tests** (45 files): `tests/Unit/DTO/` - Corresponding test files
- **Enums** (5 files): `src/Enums/` - Constants and enumerations
- **Exceptions** (11 files): `src/Exceptions/` - Error handling classes
- **Contracts** (3 files): `src/Contracts/` - Interface definitions
- **Keyboards** (4 files): `src/Keyboard/` - Keyboard builders

## Release Process

### Version Management

We follow [Semantic Versioning](https://semver.org/):

- **MAJOR** version for incompatible API changes
- **MINOR** version for backwards-compatible functionality
- **PATCH** version for backwards-compatible bug fixes

### Release Checklist

1. **Pre-release**
   - [ ] All tests pass
   - [ ] Documentation is up to date
   - [ ] CHANGELOG.md is updated
   - [ ] Version number is updated in composer.json

2. **Release**
   - [ ] Create git tag: `git tag v1.0.0`
   - [ ] Push tag: `git push origin v1.0.0`
   - [ ] Create GitHub release with changelog

3. **Post-release**
   - [ ] Verify Packagist auto-update
   - [ ] Update documentation if needed

## Getting Help

### Resources

- **Documentation**: [docs/](.)
- **Issues**: [GitHub Issues](https://github.com/FardaDev/telegram-objects-php/issues)
- **Discussions**: [GitHub Discussions](https://github.com/FardaDev/telegram-objects-php/discussions)

### Questions

If you have questions:

1. Check existing documentation
2. Search existing issues
3. Create a new discussion or issue

### Reporting Bugs

When reporting bugs, include:

- PHP version
- Library version
- Minimal code example
- Expected vs actual behavior
- Error messages (if any)

## Code of Conduct

We are committed to providing a welcoming and inclusive environment. Please:

- Be respectful and constructive
- Focus on the code and ideas, not the person
- Help others learn and grow
- Follow the [Contributor Covenant](https://www.contributor-covenant.org/)

## Recognition

Contributors are recognized in:

- GitHub contributors list
- CHANGELOG.md for significant contributions
- README.md for major features

Thank you for contributing to Telegram Objects PHP! 🚀