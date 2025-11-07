<?php

declare(strict_types=1);

/**
 * Created for: telegram-objects-php (https://github.com/FardaDev/telegram-objects-php)
 * Purpose: Exception for DTO input data validation failures
 * Created: 2025-11-06
 */

namespace Telegram\Objects\Exceptions;

/**
 * Exception thrown when input data validation fails.
 *
 * Used specifically for DTO creation and data validation scenarios.
 */
class ValidationException extends TelegramException
{
    /**
     * Create an exception for array validation failures.
     *
     * @param string $message The validation error message
     * @param string $field The field that failed validation
     * @return static
     */
    public static function invalidArrayData(string $message, string $field = ''): static
    {
        $fullMessage = $field ? "Validation failed for field '{$field}': {$message}" : "Validation failed: {$message}";

        return new static($fullMessage);
    }

    /**
     * Create an exception for type validation failures.
     *
     * @param string $field The field with invalid type
     * @param string $expectedType The expected type
     * @param mixed $actualValue The actual value received
     * @return static
     */
    public static function invalidType(string $field, string $expectedType, mixed $actualValue): static
    {
        $actualType = get_debug_type($actualValue);

        return new static("Field '{$field}' must be of type {$expectedType}, {$actualType} given");
    }

    /**
     * Create an exception for range validation failures.
     *
     * @param string $field The field with invalid range
     * @param int|float $value The actual value
     * @param int|float|null $min The minimum allowed value
     * @param int|float|null $max The maximum allowed value
     * @return static
     */
    public static function valueOutOfRange(string $field, int|float $value, int|float|null $min = null, int|float|null $max = null): static
    {
        $range = '';
        if ($min !== null && $max !== null) {
            $range = " (allowed range: {$min} to {$max})";
        } elseif ($min !== null) {
            $range = " (minimum: {$min})";
        } elseif ($max !== null) {
            $range = " (maximum: {$max})";
        }

        return new static("Field '{$field}' value {$value} is out of allowed range{$range}");
    }

    /**
     * Create an exception for string length validation failures.
     *
     * @param string $field The field with invalid length
     * @param int $actualLength The actual string length
     * @param int|null $minLength The minimum allowed length
     * @param int|null $maxLength The maximum allowed length
     * @return static
     */
    public static function invalidStringLength(string $field, int $actualLength, ?int $minLength = null, ?int $maxLength = null): static
    {
        $constraint = '';
        if ($minLength !== null && $maxLength !== null) {
            $constraint = " (allowed length: {$minLength} to {$maxLength} characters)";
        } elseif ($minLength !== null) {
            $constraint = " (minimum length: {$minLength} characters)";
        } elseif ($maxLength !== null) {
            $constraint = " (maximum length: {$maxLength} characters)";
        }

        return new static("Field '{$field}' has invalid length of {$actualLength} characters{$constraint}");
    }
}
