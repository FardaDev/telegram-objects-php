<?php

declare(strict_types=1);

/**
 * Created for: telegram-objects-php (https://github.com/FardaDev/telegram-objects-php)
 * Purpose: Framework-agnostic interface for array deserialization
 * Created: 2025-11-06
 */

namespace Telegram\Objects\Contracts;

/**
 * Interface for objects that can be created from array data.
 *
 * This interface defines the contract for DTOs that can be
 * deserialized from array data, typically from API responses.
 */
interface SerializableInterface
{
    /**
     * Create an instance from array data.
     *
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self;
}
