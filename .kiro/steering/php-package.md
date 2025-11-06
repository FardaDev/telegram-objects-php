---
inclusion: always
---

# PHP Package Development Standards

## Required Package Structure
When creating or modifying PHP packages, enforce this structure:
```
/src          # Source code with PSR-4 namespacing
/tests        # PHPUnit/Pest test files
composer.json # Package manifest with proper metadata
README.md     # Installation, usage, and examples
LICENSE       # MIT or appropriate license file
```

## Composer Configuration
- Set proper `name`, `description`, `license`, and `authors` in composer.json
- Use PSR-4 autoloading with namespace matching directory structure
- Specify minimum PHP version and required extensions
- Keep dependencies minimal and avoid framework-specific packages

## Code Quality Requirements
- **Error Handling**: Throw typed exceptions instead of returning null/false
- **Input Validation**: Validate all public method parameters
- **Static Analysis**: Write code compatible with PHPStan level 8+ or Psalm
- **Security**: Never use eval(), sanitize inputs, avoid exposing secrets

## Testing Standards
- Test all public APIs with PHPUnit or Pest
- Include edge cases and error condition tests
- Aim for high code coverage on critical paths
- Use descriptive test method names

## Documentation Requirements
- README must include installation via Composer
- Provide clear usage examples with code snippets
- Document all public methods with PHPDoc
- Include contribution guidelines if accepting PRs

## Versioning and Releases
- Follow Semantic Versioning (SemVer) strictly
- Update composer.json version for each release
- Tag releases in git with version numbers
- Document breaking changes in releases