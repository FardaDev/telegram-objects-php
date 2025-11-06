<?php declare(strict_types=1);

namespace Telegram\Objects\Support;

use Telegram\Objects\Exceptions\ValidationException;

/**
 * Utility class for input validation in DTO creation.
 * 
 * Provides common validation methods for Telegram API data.
 */
class Validator
{
    /**
     * Validate that a required field exists in the data array.
     *
     * @param array<string, mixed> $data The data array
     * @param string $field The required field name
     * @param string $context The context for error messages
     * @throws ValidationException
     */
    public static function requireField(array $data, string $field, string $context): void
    {
        if (!array_key_exists($field, $data)) {
            throw ValidationException::invalidArrayData("Missing required field '{$field}'", $context);
        }
    }

    /**
     * Validate that a field has the expected type.
     *
     * @param mixed $value The value to validate
     * @param string $expectedType The expected type (e.g., 'string', 'int', 'array')
     * @param string $field The field name for error messages
     * @throws ValidationException
     */
    public static function validateType(mixed $value, string $expectedType, string $field): void
    {
        $actualType = get_debug_type($value);
        
        if (!self::isTypeMatch($value, $expectedType)) {
            throw ValidationException::invalidType($field, $expectedType, $value);
        }
    }

    /**
     * Validate that a string field meets length requirements.
     *
     * @param string $value The string value
     * @param string $field The field name
     * @param int|null $minLength Minimum required length
     * @param int|null $maxLength Maximum allowed length
     * @throws ValidationException
     */
    public static function validateStringLength(string $value, string $field, ?int $minLength = null, ?int $maxLength = null): void
    {
        $length = mb_strlen($value, 'UTF-8');
        
        if ($minLength !== null && $length < $minLength) {
            throw ValidationException::invalidStringLength($field, $length, $minLength, $maxLength);
        }
        
        if ($maxLength !== null && $length > $maxLength) {
            throw ValidationException::invalidStringLength($field, $length, $minLength, $maxLength);
        }
    }

    /**
     * Validate that a numeric value is within the specified range.
     *
     * @param int|float $value The numeric value
     * @param string $field The field name
     * @param int|float|null $min Minimum allowed value
     * @param int|float|null $max Maximum allowed value
     * @throws ValidationException
     */
    public static function validateRange(int|float $value, string $field, int|float|null $min = null, int|float|null $max = null): void
    {
        if ($min !== null && $value < $min) {
            throw ValidationException::valueOutOfRange($field, $value, $min, $max);
        }
        
        if ($max !== null && $value > $max) {
            throw ValidationException::valueOutOfRange($field, $value, $min, $max);
        }
    }

    /**
     * Validate that a value is one of the allowed enum values.
     *
     * @param string $value The value to validate
     * @param string[] $allowedValues Array of allowed values
     * @param string $field The field name
     * @throws ValidationException
     */
    public static function validateEnum(string $value, array $allowedValues, string $field): void
    {
        if (!in_array($value, $allowedValues, true)) {
            $allowed = implode(', ', $allowedValues);
            throw ValidationException::invalidArrayData("Invalid value '{$value}' for {$field}. Allowed values: {$allowed}", $field);
        }
    }

    /**
     * Validate URL format.
     *
     * @param string $url The URL to validate
     * @param string $field The field name
     * @throws ValidationException
     */
    public static function validateUrl(string $url, string $field): void
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw ValidationException::invalidArrayData("Invalid URL format", $field);
        }
    }

    /**
     * Get a value from array with optional type validation.
     *
     * @param array<string, mixed> $data The data array
     * @param string $key The key to retrieve
     * @param mixed $default Default value if key doesn't exist
     * @param string|null $expectedType Expected type for validation
     * @return mixed
     * @throws ValidationException
     */
    public static function getValue(array $data, string $key, mixed $default = null, ?string $expectedType = null): mixed
    {
        $value = $data[$key] ?? $default;
        
        if ($expectedType !== null && $value !== null && $value !== $default) {
            self::validateType($value, $expectedType, $key);
        }
        
        return $value;
    }

    /**
     * Check if a value matches the expected type.
     *
     * @param mixed $value The value to check
     * @param string $expectedType The expected type
     * @return bool
     */
    private static function isTypeMatch(mixed $value, string $expectedType): bool
    {
        return match ($expectedType) {
            'string' => is_string($value),
            'int', 'integer' => is_int($value),
            'float', 'double' => is_float($value),
            'bool', 'boolean' => is_bool($value),
            'array' => is_array($value),
            'object' => is_object($value),
            'null' => is_null($value),
            'numeric' => is_numeric($value),
            default => get_debug_type($value) === $expectedType,
        };
    }
}