<?php declare(strict_types=1);

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
     * @return static
     */
    public static function fromArray(array $data): static;
}