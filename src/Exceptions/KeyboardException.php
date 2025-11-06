<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/src/Exceptions/KeyboardException.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-06
 */

namespace Telegram\Objects\Exceptions;

/**
 * Exception thrown for keyboard-related validation errors.
 *
 * Handles inline keyboard and reply keyboard validation failures.
 */
class KeyboardException extends TelegramException
{
    /**
     * Create an exception for invalid button configurations.
     *
     * @param string $reason The reason why the button configuration is invalid
     * @return static
     */
    public static function invalidButtonConfiguration(string $reason): static
    {
        return new static("Invalid button configuration: {$reason}");
    }

    /**
     * Create an exception for callback data length validation.
     *
     * @param int $actualLength The actual callback data length
     * @param int $maxLength The maximum allowed length
     * @return static
     */
    public static function callbackDataTooLong(int $actualLength, int $maxLength): static
    {
        return new static("Callback data length ({$actualLength} bytes) exceeds maximum allowed length of {$maxLength} bytes");
    }

    /**
     * Create an exception for invalid URL formats.
     *
     * @param string $url The invalid URL
     * @return static
     */
    public static function invalidUrl(string $url): static
    {
        return new static("Invalid URL format: {$url}");
    }

    /**
     * Create an exception for keyboard size limitations.
     *
     * @param int $buttonCount The number of buttons
     * @param int $maxButtons The maximum allowed buttons
     * @return static
     */
    public static function tooManyButtons(int $buttonCount, int $maxButtons): static
    {
        return new static("Keyboard has {$buttonCount} buttons, but maximum allowed is {$maxButtons}");
    }

    /**
     * Create an exception for invalid button text.
     *
     * @param string $text The invalid button text
     * @param string $reason The reason why the text is invalid
     * @return static
     */
    public static function invalidButtonText(string $text, string $reason): static
    {
        return new static("Invalid button text '{$text}': {$reason}");
    }

    /**
     * Create an exception for placeholder text validation.
     *
     * @param string $placeholder The invalid placeholder text
     * @param int $maxLength The maximum allowed length
     * @return static
     */
    public static function placeholderTooLong(string $placeholder, int $maxLength): static
    {
        return new static("Placeholder text '{$placeholder}' exceeds maximum length of {$maxLength} characters");
    }
}
