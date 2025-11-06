# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.1.0-beta] - 2025-11-07

### Added
- Initial beta release of telegram-objects-php library
- Complete set of Telegram Bot API DTOs extracted from DefStudio/Telegraph
- Framework-agnostic implementation with PHP 8.1+ support
- Comprehensive test suite with 100% coverage of core functionality
- Keyboard builders for inline and reply keyboards
- Upstream synchronization system for tracking Telegraph changes
- Full documentation and examples

### Features

#### Core DTOs (45 classes)
- **Core Objects**: `TelegramUpdate`, `Message`, `User`, `Chat`
- **Media Objects**: `Photo`, `Video`, `Audio`, `Voice`, `Document`, `Animation`, `Sticker`
- **Location Objects**: `Location`, `Venue`, `Contact`
- **Interactive Objects**: `CallbackQuery`, `InlineQuery`, `Poll`, `PollAnswer`, `PollOption`
- **Payment Objects**: `Invoice`, `SuccessfulPayment`, `RefundedPayment`, `PreCheckoutQuery`, `OrderInfo`
- **Administrative Objects**: `ChatMember`, `ChatMemberUpdate`, `ChatInviteLink`, `ChatJoinRequest`
- **Utility Objects**: `Entity`, `Reaction`, `ReactionType`, `WriteAccessAllowed`

#### Keyboard System (4 classes)
- `Keyboard` - Inline keyboard builder
- `Button` - Inline keyboard buttons with actions, URLs, web apps
- `ReplyKeyboard` - Reply keyboard builder with options
- `ReplyButton` - Reply keyboard buttons with contact/location requests

#### Enums (5 classes)
- `ChatActions` - Chat action constants (typing, uploading, etc.)
- `ChatAdminPermissions` - Admin permission constants
- `ChatPermissions` - User permission constants
- `Emojis` - Emoji constants for polls and games
- `ReplyButtonType` - Reply button type constants

#### Exceptions (11 classes)
- `TelegramException` - Base exception class
- `ValidationException` - Input validation errors
- `FileException` - File handling errors
- `KeyboardException` - Keyboard validation errors
- Plus 7 additional specialized exception types

#### Support Classes
- `Collection` - Lightweight collection implementation
- `TelegramDateTime` - Date/time handling without Carbon dependency
- `Validator` - Input validation utilities
- `ArrayableInterface` - Framework-agnostic arrayable contract
- `SerializableInterface` - Object serialization contract
- `DownloadableInterface` - File download interface

### Development Tools

#### Testing
- Comprehensive PHPUnit/Pest test suite (200+ tests)
- PHPStan static analysis at level 8
- PHP-CS-Fixer for code style compliance
- Test coverage reporting

#### Upstream Synchronization
- Python-based upstream tracking system
- Automatic change detection from DefStudio/Telegraph
- Diff report generation for code changes
- Composer integration for easy usage

#### Documentation
- Complete README with usage examples
- Contributing guidelines
- Upstream synchronization documentation
- Code examples and best practices

### Technical Details

#### Requirements
- PHP 8.1 or higher
- No runtime dependencies
- PSR-4 autoloading
- PSR-12 code style compliance

#### Attribution
- All extracted files include proper attribution headers
- Tracking of upstream Telegraph commit hashes
- Maintenance of original licensing and credits

#### Quality Assurance
- Strict typing throughout the codebase
- Comprehensive input validation
- Immutable object design where appropriate
- Framework-agnostic implementation

## [0.1.0-beta] - 2025-11-07

### Added
- Initial beta release for community testing
- Complete Telegram Bot API DTO coverage
- Framework-agnostic design
- Comprehensive documentation
- Full test suite
- Upstream synchronization system

### Beta Notes
- This is a beta release for community testing and feedback
- API may change based on user feedback
- Production use is not recommended until stable release

---

## Attribution

This library is extracted and adapted from [DefStudio/Telegraph](https://github.com/defstudio/telegraph).

**Original Project**: DefStudio/Telegraph  
**License**: MIT  
**Upstream Commit**: 0f4a6cf45a902e7136a5bbafda26bec36a10e748  
**Extraction Date**: 2025-11-07

## Links

- [Repository](https://github.com/FardaDev/telegram-objects-php)
- [Issues](https://github.com/FardaDev/telegram-objects-php/issues)
- [Packagist](https://packagist.org/packages/fardadev/telegram-objects-php)
- [Upstream Project](https://github.com/defstudio/telegraph)