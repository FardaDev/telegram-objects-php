<?php

declare(strict_types=1);

namespace Telegram\Objects\Exceptions;

/**
 * Exception thrown during object serialization and deserialization.
 *
 * Handles errors that occur when converting between arrays and DTOs.
 */
class SerializationException extends TelegramException
{
    /**
     * Create an exception for circular reference detection.
     *
     * @param string $className The class where circular reference was detected
     * @return static
     */
    public static function circularReference(string $className): static
    {
        return new static("Circular reference detected in {$className} during serialization");
    }

    /**
     * Create an exception for unsupported data types during serialization.
     *
     * @param mixed $value The unsupported value
     * @param string $context The context where serialization failed
     * @return static
     */
    public static function unsupportedType(mixed $value, string $context): static
    {
        $type = get_debug_type($value);

        return new static("Unsupported type '{$type}' encountered during serialization in {$context}");
    }

    /**
     * Create an exception for malformed array data during deserialization.
     *
     * @param string $expectedStructure Description of expected array structure
     * @param string $context The context where deserialization failed
     * @return static
     */
    public static function malformedArrayData(string $expectedStructure, string $context): static
    {
        return new static("Malformed array data in {$context}. Expected: {$expectedStructure}");
    }

    /**
     * Create an exception for nested object creation failures.
     *
     * @param string $className The class that failed to be created
     * @param string $parentContext The parent object context
     * @param \Throwable $previous The underlying exception
     * @return static
     */
    public static function nestedObjectCreationFailed(string $className, string $parentContext, \Throwable $previous): static
    {
        return new static("Failed to create nested {$className} object in {$parentContext}: {$previous->getMessage()}", 0, $previous);
    }

    /**
     * Create an exception for JSON encoding/decoding failures.
     *
     * @param string $operation The operation that failed ('encode' or 'decode')
     * @param string $error The JSON error message
     * @return static
     */
    public static function jsonError(string $operation, string $error): static
    {
        return new static("JSON {$operation} failed: {$error}");
    }
}
