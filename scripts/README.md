# Scripts Directory

This directory contains development and maintenance scripts for the telegram-objects-php library.

## Files

- **`check-upstream.py`** - Main upstream synchronization script
- **`requirements.txt`** - Python dependencies for the scripts

## Usage

### Install Dependencies

```bash
pip install -r requirements.txt
```

### Check for Upstream Updates

```bash
python check-upstream.py --check
```

### View Status

```bash
python check-upstream.py --status
```

### Generate Diff Report

```bash
python check-upstream.py --diff [FROM_COMMIT] [TO_COMMIT] --output report.md
```

### Update Tracking

```bash
python check-upstream.py --update [COMMIT_HASH]
```

## Composer Integration

These scripts are also available via Composer:

```bash
composer run upstream:install  # Install Python dependencies
composer run upstream:check    # Check for updates
composer run upstream:status   # View status
composer run upstream:test     # Run tests
```

## Testing

The upstream tracking functionality includes comprehensive unit tests:

```bash
# Run tests directly
python scripts/run_tests.py

# Run tests with verbose output
python scripts/run_tests.py -v

# Run via Composer
composer run upstream:test
```

For detailed documentation, see [docs/upstream-sync.md](../docs/upstream-sync.md).