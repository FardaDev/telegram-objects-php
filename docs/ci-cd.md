# CI/CD Documentation

This document describes the continuous integration and deployment setup for telegram-objects-php.

## GitHub Actions Workflows

### 1. Tests (`tests.yml`)

**Triggers:** Push/PR to `main` or `develop` branches

**Matrix Testing:**
- PHP versions: 8.1, 8.2, 8.3
- Operating systems: Ubuntu, Windows
- Total: 6 test combinations

**Steps:**
1. Code style checks (`composer test:lint`)
2. Static analysis with PHPStan level 8 (`composer test:types`)
3. Unit tests with Pest (`composer test:unit`)

### 2. Coverage (`coverage.yml`)

**Triggers:** Push/PR to `main` branch

**Purpose:** Generate and upload code coverage reports

**Steps:**
1. Run tests with Xdebug coverage
2. Upload coverage to Codecov
3. Generate coverage badge

### 3. Upstream Sync (`upstream-sync.yml`)

**Triggers:** 
- Weekly schedule (Mondays at 9:00 AM UTC)
- Manual dispatch

**Purpose:** Monitor DefStudio/Telegraph for changes

**Steps:**
1. Run upstream sync tests
2. Check for Telegraph repository changes
3. Create GitHub issue if changes detected

**Auto-created Issue:**
- Title: "🔄 Upstream Telegraph Changes Detected"
- Labels: `upstream`, `sync-required`
- Body: Includes change report and sync guidelines

### 4. Security (`security.yml`)

**Triggers:**
- Push/PR to `main` branch
- Daily schedule (2:00 AM UTC)

**Purpose:** Check for security vulnerabilities

**Steps:**
1. Run `composer audit`
2. Report any vulnerable dependencies

### 5. Release (`release.yml`)

**Triggers:** Push tags matching `v*.*.*`

**Purpose:** Automated release creation

**Steps:**
1. Validate composer.json
2. Run full test suite
3. Extract changelog for version
4. Create GitHub release
5. Mark as prerelease if version contains "beta" or "alpha"

## Dependabot Configuration

Automated dependency updates for:

### Composer Dependencies
- Schedule: Weekly (Mondays)
- Max PRs: 5
- Labels: `dependencies`, `composer`

### GitHub Actions
- Schedule: Weekly (Mondays)
- Max PRs: 5
- Labels: `dependencies`, `github-actions`

### Python Dependencies
- Schedule: Weekly (Mondays)
- Max PRs: 3
- Labels: `dependencies`, `python`
- Directory: `/scripts`

## Badges

The following badges are displayed in README.md:

- **Tests**: Shows test workflow status
- **Coverage**: Shows coverage workflow status
- **Security**: Shows security audit status
- **PHP Version**: Minimum PHP version requirement
- **License**: MIT license badge

## Local Development

Run the same checks locally before pushing:

```bash
# Full test suite (lint + types + unit)
composer test

# Individual checks
composer test:lint    # Code style
composer test:types   # Static analysis
composer test:unit    # Unit tests

# Coverage report
composer coverage

# Upstream sync check
composer upstream:check
composer upstream:status
```

## Workflow Permissions

Required GitHub repository settings:

- **Actions**: Read and write permissions
- **Issues**: Write permissions (for upstream sync alerts)
- **Contents**: Write permissions (for releases)

## Secrets

No secrets required for basic workflows. Optional:

- `CODECOV_TOKEN`: For private repository coverage (public repos don't need this)

## Best Practices

1. **All PRs must pass tests** before merging
2. **Security alerts** should be addressed promptly
3. **Upstream sync issues** should be reviewed weekly
4. **Dependabot PRs** should be reviewed and merged regularly
5. **Release tags** should follow semantic versioning (v1.0.0, v0.1.0-beta, etc.)

## Troubleshooting

### Tests failing on Windows
- Verify path separators in tests (use `/` not `\`)

### Coverage upload fails
- Ensure Codecov token is set (if private repo)
- Check coverage.xml is generated

### Upstream sync not creating issues
- Verify GitHub token has issues write permission
- Check workflow logs for errors

### Release workflow not triggering
- Ensure tag format matches `v*.*.*`
- Verify tag is pushed: `git push origin v1.0.0`
