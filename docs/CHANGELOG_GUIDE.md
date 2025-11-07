# CHANGELOG Management Guide

## Overview

This project uses a **hybrid approach** to CHANGELOG management:
- **Automated generation** from conventional commits
- **Manual curation** for clarity and user focus

## Tools for Automated CHANGELOG

### 1. conventional-changelog (Recommended)

**Best for**: JavaScript/Node projects, but works with any language

```bash
# Install globally
npm install -g conventional-changelog-cli

# Generate CHANGELOG
conventional-changelog -p angular -i CHANGELOG.md -s

# First release (overwrites)
conventional-changelog -p angular -i CHANGELOG.md -s -r 0
```

**Pros:**
- Industry standard
- Works with conventional commits
- Highly configurable
- Used by Angular, Vue, React

**Cons:**
- Requires Node.js

### 2. git-cliff (Recommended for PHP)

**Best for**: Any language, no Node.js required

```bash
# Install (various methods)
cargo install git-cliff  # Rust
brew install git-cliff   # macOS
# Or download binary from GitHub

# Generate CHANGELOG
git-cliff -o CHANGELOG.md

# For specific version
git-cliff --tag v1.0.0 -o CHANGELOG.md
```

**Pros:**
- No Node.js dependency
- Fast (written in Rust)
- Highly customizable
- Beautiful output

**Cons:**
- Requires separate installation

### 3. GitHub Actions (Automated on Release)

**Best for**: Fully automated workflow

Create `.github/workflows/changelog.yml`:

```yaml
name: Update CHANGELOG

on:
  release:
    types: [published]

jobs:
  changelog:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
        with:
          fetch-depth: 0
      
      - name: Generate CHANGELOG
        uses: orhun/git-cliff-action@v3
        with:
          config: cliff.toml
          args: --verbose
        env:
          OUTPUT: CHANGELOG.md
      
      - name: Commit CHANGELOG
        run: |
          git config user.name "github-actions[bot]"
          git config user.email "github-actions[bot]@users.noreply.github.com"
          git add CHANGELOG.md
          git commit -m "docs: update CHANGELOG for ${{ github.event.release.tag_name }}"
          git push
```

## Our Workflow

### Before Each Release

1. **Review commits since last release:**
   ```bash
   git log v0.1.0-beta..HEAD --oneline --no-merges
   ```

2. **Generate draft CHANGELOG:**
   ```bash
   # Using git-cliff (recommended)
   git-cliff --unreleased --tag v0.2.0 --prepend CHANGELOG.md
   
   # OR using conventional-changelog
   conventional-changelog -p angular -i CHANGELOG.md -s
   ```

3. **Manually edit for clarity:**
   - Group related changes
   - Rewrite technical commits in user-friendly language
   - Add context and examples
   - Highlight breaking changes
   - Remove noise (chore, style, test commits)

4. **Add to Unreleased section:**
   ```markdown
   ## [Unreleased]
   
   ### Added
   - New feature X that allows users to Y
   
   ### Fixed
   - Bug where Z would fail under condition W
   ```

### On Release

1. **Move Unreleased to versioned section:**
   ```markdown
   ## [0.2.0] - 2025-11-15
   
   ### Added
   - New feature X that allows users to Y
   
   ### Fixed
   - Bug where Z would fail under condition W
   
   ## [0.1.0-beta] - 2025-11-07
   ...
   ```

2. **Add comparison links at bottom:**
   ```markdown
   [Unreleased]: https://github.com/FardaDev/telegram-objects-php/compare/v0.2.0...HEAD
   [0.2.0]: https://github.com/FardaDev/telegram-objects-php/compare/v0.1.0-beta...v0.2.0
   [0.1.0-beta]: https://github.com/FardaDev/telegram-objects-php/releases/tag/v0.1.0-beta
   ```

3. **Commit and tag:**
   ```bash
   git add CHANGELOG.md
   git commit -m "docs: update CHANGELOG for v0.2.0"
   git tag -a v0.2.0 -m "Release v0.2.0"
   git push origin main --tags
   ```

## Conventional Commit → CHANGELOG Mapping

| Commit Type | CHANGELOG Section | Include? |
|-------------|-------------------|----------|
| `feat:` | Added | ✅ Yes |
| `fix:` | Fixed | ✅ Yes |
| `perf:` | Changed | ✅ Yes |
| `refactor:` | Changed | ⚠️ If user-facing |
| `docs:` | Changed | ⚠️ If significant |
| `style:` | - | ❌ No |
| `test:` | - | ❌ No |
| `chore:` | - | ⚠️ If deps update |
| `build:` | - | ⚠️ If affects users |
| `ci:` | - | ❌ No |

**Breaking Changes** (any type with `!` or `BREAKING CHANGE:`):
- Always include in CHANGELOG
- Put in separate "Breaking Changes" section
- Explain migration path

## Writing Good CHANGELOG Entries

### ❌ Bad (Too Technical)
```markdown
### Changed
- Refactored UserDTO constructor to use named parameters
- Updated PHPStan to v2.1
```

### ✅ Good (User-Focused)
```markdown
### Changed
- Improved type safety in User object creation
- Updated static analysis tools for better error detection

### Developer Notes
- PHPStan upgraded to v2.1 (stricter type checking)
```

### ❌ Bad (Too Vague)
```markdown
### Fixed
- Fixed bug
- Improved performance
```

### ✅ Good (Specific)
```markdown
### Fixed
- Fixed keyboard serialization failing with special characters (#42)
- Improved message parsing performance by 40% for large payloads
```

## CHANGELOG Template

```markdown
# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- New features go here

### Changed
- Changes to existing functionality

### Deprecated
- Soon-to-be removed features

### Removed
- Removed features

### Fixed
- Bug fixes

### Security
- Security fixes

## [1.0.0] - 2025-11-15

### Added
- Initial stable release
- Feature X
- Feature Y

### Breaking Changes
- Changed API method signatures (see migration guide)

## [0.1.0-beta] - 2025-11-07

### Added
- Initial beta release

[Unreleased]: https://github.com/user/repo/compare/v1.0.0...HEAD
[1.0.0]: https://github.com/user/repo/compare/v0.1.0-beta...v1.0.0
[0.1.0-beta]: https://github.com/user/repo/releases/tag/v0.1.0-beta
```

## Automation Setup

### Option 1: git-cliff (Recommended)

1. **Install git-cliff:**
   ```bash
   # Windows (download from GitHub releases)
   # https://github.com/orhun/git-cliff/releases
   
   # Or use cargo
   cargo install git-cliff
   ```

2. **Create `cliff.toml` config:**
   ```toml
   [changelog]
   header = """
   # Changelog\n
   All notable changes to this project will be documented in this file.\n
   """
   body = """
   {% for group, commits in commits | group_by(attribute="group") %}
       ### {{ group | upper_first }}
       {% for commit in commits %}
           - {{ commit.message | upper_first }}\
       {% endfor %}
   {% endfor %}
   """
   
   [git]
   conventional_commits = true
   filter_unconventional = true
   commit_parsers = [
       { message = "^feat", group = "Added" },
       { message = "^fix", group = "Fixed" },
       { message = "^perf", group = "Changed" },
       { message = "^refactor", group = "Changed" },
       { message = "^chore\\(deps\\)", group = "Dependencies" },
   ]
   ```

3. **Add to composer scripts:**
   ```json
   {
     "scripts": {
       "changelog": "git-cliff --unreleased --prepend CHANGELOG.md"
     }
   }
   ```

### Option 2: Manual with Helper Script

Create `scripts/update-changelog.sh`:

```bash
#!/bin/bash

# Get last tag
LAST_TAG=$(git describe --tags --abbrev=0 2>/dev/null || echo "")

if [ -z "$LAST_TAG" ]; then
    echo "No previous tags found. Showing all commits:"
    git log --oneline --no-merges
else
    echo "Changes since $LAST_TAG:"
    git log $LAST_TAG..HEAD --oneline --no-merges
fi

echo ""
echo "Categorized by type:"
echo ""
echo "Added (feat):"
git log $LAST_TAG..HEAD --oneline --no-merges --grep="^feat"
echo ""
echo "Fixed (fix):"
git log $LAST_TAG..HEAD --oneline --no-merges --grep="^fix"
echo ""
echo "Changed (refactor/perf):"
git log $LAST_TAG..HEAD --oneline --no-merges --grep="^refactor\|^perf"
```

## Best Practices Summary

1. ✅ **Use conventional commits** - Makes automation possible
2. ✅ **Update before release** - Not after
3. ✅ **Be user-focused** - Write for users, not developers
4. ✅ **Group related changes** - Don't list every commit
5. ✅ **Highlight breaking changes** - Make them obvious
6. ✅ **Link to issues/PRs** - Provide context
7. ✅ **Keep it chronological** - Newest first
8. ✅ **Use semantic versioning** - Follow semver strictly
9. ✅ **Review and edit** - Auto-generated is a starting point
10. ✅ **Add comparison links** - Help users see diffs

## Resources

- [Keep a Changelog](https://keepachangelog.com/)
- [Semantic Versioning](https://semver.org/)
- [Conventional Commits](https://www.conventionalcommits.org/)
- [git-cliff](https://github.com/orhun/git-cliff)
- [conventional-changelog](https://github.com/conventional-changelog/conventional-changelog)
