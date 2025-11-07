<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: src/DTO/InlineQueryResult.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-06
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\SerializableInterface;

/**
 * Base class for inline query results.
 *
 * This abstract class represents the base for all inline query result types.
 */
abstract class InlineQueryResult implements ArrayableInterface, SerializableInterface
{
    /**
     * @param string $type Type of the result
     * @param string $id Unique identifier for this result, 1-64 bytes
     */
    protected function __construct(
        protected readonly string $type,
        protected readonly string $id,
    ) {
    }

    /**
     * Get the type of the result.
     *
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * Get the unique identifier for this result.
     *
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * Create an InlineQueryResult instance from array data.
     * This method should be implemented by concrete classes.
     *
     * @param array<string, mixed> $data The inline query result data from Telegram API
     * @return static
     */
    abstract public static function fromArray(array $data): InlineQueryResult;

    /**
     * Convert the InlineQueryResult to an array.
     * This method should be implemented by concrete classes.
     *
     * @return array<string, mixed>
     */
    abstract public function toArray(): array;
}
