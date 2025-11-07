# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.1.0-beta] - 2025-11-07

### Added

- **Complete Telegram Bot API DTOs** - 37 data transfer objects covering all major Telegram types
  - Core objects: `TelegramUpdate`, `Message`, `User`, `Chat`
  - Media objects: `Photo`, `Video`, `Audio`, `Voice`, `Document`, `Animation`, `Sticker`
  - Interactive objects: `CallbackQuery`, `InlineQuery`, `Poll`, `PollAnswer`
  - Payment objects: `Invoice`, `SuccessfulPayment`, `RefundedPayment`, `PreCheckoutQuery`
  - Administrative objects: `ChatMember`, `ChatMemberUpdate`, `ChatInviteLink`, `ChatJoinRequest`

- **Keyboard Builders** - Fluent API for creating Telegram keyboards
  - `Keyboard` - Inline keyboard with callback buttons, URLs, and web apps
  - `ReplyKeyboard` - Reply keyboard with custom buttons and options
  - Support for contact/location request buttons
  - Keyboard validation and serialization

- **Type-Safe Enums** - PHP 8.1 enums for Telegram constants
  - `ChatActions` - Typing, uploading, recording actions
  - `ChatPermissions` - User permission management
  - `ChatAdminPermissions` - Admin permission management
  - `Emojis` - Dice and slot machine emojis
  - `ReplyButtonType` - Button type constants

- **Exception Hierarchy** - Specialized exceptions for better error handling
  - `TelegramException` - Base exception
  - `ValidationException` - Input validation errors
  - `KeyboardException` - Keyboard validation errors
  - `FileException` - File handling errors
  - `PaymentException` - Payment processing errors
  - `SerializationException` - Object serialization errors

- **Support Utilities** - Framework-agnostic helper classes
  - `Collection` - Lightweight collection implementation (no dependencies)
  - `TelegramDateTime` - Date/time handling without Carbon
  - `Validator` - Input validation utilities
  - Helper functions for common operations

- **Upstream Synchronization** - Python-based tracking system
  - Automatic change detection from DefStudio/Telegraph
  - Diff report generation for code changes
  - Commit hash tracking for attribution
  - Composer integration for easy usage

- **Comprehensive Documentation**
  - Installation and usage guide
  - Code examples for common scenarios
  - API reference for all classes
  - Contributing guidelines
  - Upstream synchronization guide

### Technical Details

- **PHP 8.1+ Required** - Uses modern PHP features (enums, readonly properties, union types)
- **Zero Runtime Dependencies** - Framework-agnostic, works with any PHP project
- **PSR-4 Autoloading** - Standard autoloading for easy integration
- **PSR-12 Code Style** - Consistent, readable code
- **Strict Typing** - Full type safety throughout the codebase
- **Immutable Objects** - DTOs are immutable by design
- **Comprehensive Tests** - 506 tests with 1828 assertions
- **PHPStan Level 8** - Maximum static analysis strictness
- **100% Test Coverage** - All core functionality tested

### Attribution

This library is extracted and adapted from [DefStudio/Telegraph](https://github.com/defstudio/telegraph).

- **Original Project**: DefStudio/Telegraph
- **License**: MIT
- **Upstream Commit**: `0f4a6cf45a902e7136a5bbafda26bec36a10e748`
- **Extraction Date**: 2025-11-07

All extracted files include proper attribution headers with upstream commit tracking.

### Beta Notes

⚠️ **This is a beta release** for community testing and feedback.

- API may change based on user feedback
- Not recommended for production use until stable 1.0.0 release
- Please report issues on [GitHub](https://github.com/FardaDev/telegram-objects-php/issues)

[Unreleased]: https://github.com/FardaDev/telegram-objects-php/compare/v0.1.0-beta...HEAD
[0.1.0-beta]: https://github.com/FardaDev/telegram-objects-php/releases/tag/v0.1.0-beta