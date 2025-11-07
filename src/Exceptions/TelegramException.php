<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: src/Exceptions/TelegraphException.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-06
 */

namespace Telegram\Objects\Exceptions;

/**
 * Base exception for all Telegram Objects library errors.
 *
 * Provides a foundation for all specialized exceptions in the library.
 */
class TelegramException extends \Exception
{
    /**
     * Create an exception for invalid data provided to DTO creation.
     *
     * @param string $field The field that contains invalid data
     * @param mixed $value The invalid value
     * @param string $expectedType The expected data type or format
     * @return static
     */
    public static function invalidData(string $field, mixed $value, string $expectedType): static
    {
        $valueType = get_debug_type($value);

        return new static("Invalid data for field '{$field}': expected {$expectedType}, got {$valueType}");
    }

    /**
     * Create an exception for missing required fields.
     *
     * @param string $field The missing required field
     * @param string $context The context where the field is required (e.g., DTO class name)
     * @return static
     */
    public static function missingRequiredField(string $field, string $context): static
    {
        return new static("Missing required field '{$field}' in {$context}");
    }

    /**
     * Create an exception for invalid enum values.
     *
     * @param string $value The invalid enum value
     * @param string[] $allowedValues List of allowed values
     * @param string $context The context where the enum is used
     * @return static
     */
    public static function invalidEnumValue(string $value, array $allowedValues, string $context): static
    {
        $allowed = implode(', ', $allowedValues);

        return new static("Invalid value '{$value}' for {$context}. Allowed values: {$allowed}");
    }
}
