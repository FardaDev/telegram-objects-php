# Publishing Guide

Quick guide for publishing releases to Packagist.

## First-Time Setup

### 1. Submit to Packagist

1. Go to https://packagist.org/packages/submit
2. Enter repository URL: `https://github.com/FardaDev/telegram-objects-php`
3. Click "Check"
4. Click "Submit"

### 2. Enable Auto-Update Hook

Packagist will show instructions to add a webhook to GitHub. This makes Packagist auto-update on new releases.

**Or manually:** Settings → Webhooks → Add webhook
- URL: `https://packagist.org/api/github?username=FardaDev`
- Content type: `application/json`
- Events: Just the push event

## Publishing a Release

### 1. Update Version

Update `CHANGELOG.md`:

```markdown
## [Unreleased]

## [0.2.0] - 2025-11-15

### Added
- New feature X

### Fixed
- Bug Y
```

### 2. Create Git Tag

```bash
# Create annotated tag
git tag -a v0.2.0 -m "Release v0.2.0"

# Push tag
git push origin v0.2.0
```

### 3. Create GitHub Release

GitHub Actions will automatically create a release, or manually:

1. Go to https://github.com/FardaDev/telegram-objects-php/releases/new
2. Choose tag: `v0.2.0`
3. Title: `v0.2.0`
4. Description: Copy from CHANGELOG.md
5. Click "Publish release"

### 4. Verify

- Check Packagist: https://packagist.org/packages/fardadev/telegram-objects-php
- Verify version appears
- Test installation: `composer require fardadev/telegram-objects-php:^0.2.0`

## Version Numbering

Follow [Semantic Versioning](https://semver.org/):

- **Major (1.0.0)**: Breaking changes
- **Minor (0.2.0)**: New features, backward compatible
- **Patch (0.1.1)**: Bug fixes, backward compatible

### Pre-releases

- **Beta**: `0.1.0-beta`, `0.1.0-beta.2`
- **RC**: `1.0.0-rc.1`, `1.0.0-rc.2`

## Checklist

Before releasing:

- [ ] All tests passing
- [ ] CHANGELOG.md updated
- [ ] Version number decided
- [ ] No uncommitted changes
- [ ] CI/CD workflows green

## Troubleshooting

**Packagist not updating?**
- Check webhook is configured
- Manually trigger update on Packagist package page

**Tag already exists?**
```bash
# Delete local tag
git tag -d v0.2.0

# Delete remote tag
git push origin :refs/tags/v0.2.0

# Create new tag
git tag -a v0.2.0 -m "Release v0.2.0"
git push origin v0.2.0
```

**Wrong version published?**
- Delete the tag (see above)
- Create correct tag
- Packagist will update automatically
