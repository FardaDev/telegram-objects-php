# Upstream Synchronization

This document explains how to maintain synchronization with the upstream [DefStudio/Telegraph](https://github.com/defstudio/telegraph) repository.

## Overview

The `telegram-objects-php` library is extracted from the DefStudio/Telegraph Laravel package. To keep our library up-to-date with upstream changes, we use a Python-based tracking system that monitors the Telegraph repository for updates.

## Setup

### Prerequisites

- Python 3.7 or higher
- Git
- Access to the `vendor_sources/telegraph` directory (Telegraph repository clone)

### Installation

1. Install Python dependencies:
   ```bash
   composer run upstream:install
   # or directly:
   pip install -r scripts/requirements.txt
   ```

2. Verify the setup:
   ```bash
   composer run upstream:status
   ```

## Usage

### Check for Updates

To check if there are new commits in the upstream Telegraph repository:

```bash
composer run upstream:check
```

This will:
- Fetch the latest changes from the Telegraph repository
- Compare with our last tracked commit
- Generate a diff report if changes are found
- Save the report as `diff-[old]-to-[new].md`

### View Current Status

To see the current synchronization status:

```bash
composer run upstream:status
```

This displays:
- Current tracked commit
- Last check timestamp
- Number of extracted files
- Synchronization status

### Generate Diff Reports

To generate a diff report between specific commits:

```bash
python scripts/check-upstream.py --diff [FROM_COMMIT] [TO_COMMIT] --output report.md
```

### Update Tracking

After manually reviewing and applying upstream changes, update the tracking:

```bash
python scripts/check-upstream.py --update [NEW_COMMIT_HASH]
```

## Configuration

The synchronization is configured via `upstream.json`:

```json
{
  "repository": {
    "name": "defstudio/telegraph",
    "url": "https://github.com/defstudio/telegraph",
    "local_path": "vendor_sources/telegraph"
  },
  "tracking": {
    "current_commit": "abc123...",
    "last_checked": "2025-11-07T10:30:00",
    "last_sync_commit": "abc123..."
  },
  "files": {
    "extracted_count": 118,
    "categories": {
      "dto_classes": 45,
      "dto_tests": 45,
      "enums": 5,
      "exceptions": 11,
      "contracts": 3,
      "keyboards": 4,
      "support": 5
    }
  }
}
```

## Workflow

### Regular Maintenance

1. **Weekly Check**: Run `composer run upstream:check` to see if there are updates
2. **Review Changes**: Examine the generated diff report
3. **Apply Changes**: Manually update affected files in our library
4. **Test**: Run the full test suite to ensure compatibility
5. **Update Tracking**: Use `--update` to mark the new commit as processed

### Handling Updates

When upstream changes are detected:

1. **Analyze Impact**: Review the diff report to understand what changed
2. **Categorize Changes**:
   - **DTO Changes**: Update corresponding files in `src/DTO/`
   - **Test Changes**: Update corresponding files in `tests/Unit/DTO/`
   - **New Features**: Decide if they should be included
   - **Breaking Changes**: Plan migration strategy

3. **Update Files**: Apply necessary changes while maintaining our framework-agnostic approach
4. **Update Attribution**: Ensure attribution headers reflect the new commit
5. **Test Thoroughly**: Run all tests and static analysis
6. **Update Tracking**: Mark the changes as processed

### File Categories

Our extraction covers these Telegraph components:

- **DTO Classes** (45 files): Core Telegram API objects
- **DTO Tests** (45 files): Corresponding test files
- **Enums** (5 files): Constants and enumerations
- **Exceptions** (11 files): Error handling classes
- **Contracts** (3 files): Interface definitions
- **Keyboards** (4 files): Inline and reply keyboard builders
- **Support** (5 files): Utility classes

## Troubleshooting

### Common Issues

**Git Repository Not Found**
```
Error: Repository path vendor_sources/telegraph not found
```
Solution: Ensure the Telegraph repository is cloned in the correct location.

**GitPython Not Installed**
```
Error: GitPython is required. Install with: pip install gitpython
```
Solution: Run `composer run upstream:install` or `pip install gitpython`.

**Permission Errors**
```
Error: Permission denied when writing upstream.json
```
Solution: Check file permissions and ensure the script has write access.

### Manual Recovery

If the tracking gets out of sync:

1. Find the current Telegraph commit:
   ```bash
   cd vendor_sources/telegraph
   git rev-parse HEAD
   ```

2. Update the tracking manually:
   ```bash
   python scripts/check-upstream.py --update [COMMIT_HASH]
   ```

3. Verify the status:
   ```bash
   composer run upstream:status
   ```

## Best Practices

1. **Regular Monitoring**: Check for updates weekly
2. **Incremental Updates**: Don't let too many commits accumulate
3. **Test Everything**: Always run the full test suite after updates
4. **Document Changes**: Update CHANGELOG.md with significant changes
5. **Backup First**: Commit current state before applying upstream changes
6. **Review Carefully**: Not all upstream changes may be relevant to our library

## Attribution

All extracted files maintain attribution headers pointing to their Telegraph source:

```php
/**
 * Extracted from: vendor_sources/telegraph/src/DTO/User.php
 * Telegraph commit: abc123...
 * Date: 2025-11-07
 */
```

When updating files, ensure these headers reflect the new commit hash.