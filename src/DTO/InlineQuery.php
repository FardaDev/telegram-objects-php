<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: src/DTO/InlineQuery.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-06
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Support\Validator;

/**
 * Represents an incoming inline query.
 *
 * This object represents an incoming inline query. When the user sends an empty query,
 * your bot will receive an update with an empty query string.
 */
class InlineQuery implements ArrayableInterface, SerializableInterface
{
    /**
     * @param string $id Unique identifier for this query
     * @param User $from Sender
     * @param string $query Text of the query (up to 256 characters)
     * @param string $offset Offset of the results to be returned, can be controlled by the bot
     * @param string|null $chatType Type of the chat from which the inline query was sent
     * @param Location|null $location Sender location, only for bots that request user location
     */
    private function __construct(
        private readonly string $id,
        private readonly User $from,
        private readonly string $query,
        private readonly string $offset,
        private readonly ?string $chatType = null,
        private readonly ?Location $location = null,
    ) {
    }

    /**
     * Create an InlineQuery instance from array data.
     *
     * @param array<string, mixed> $data The inline query data from Telegram API
     * @return self
     */
    public static function fromArray(array $data): self
    {
        Validator::requireField($data, 'id', 'InlineQuery');
        Validator::requireField($data, 'from', 'InlineQuery');
        Validator::requireField($data, 'query', 'InlineQuery');
        Validator::requireField($data, 'offset', 'InlineQuery');

        $id = Validator::getValue($data, 'id', null, 'string');

        $fromData = Validator::getValue($data, 'from', null, 'array');
        $from = User::fromArray($fromData);

        $query = Validator::getValue($data, 'query', '', 'string');
        $offset = Validator::getValue($data, 'offset', '', 'string');
        $chatType = Validator::getValue($data, 'chat_type', null, 'string');

        $location = null;
        if (isset($data['location']) && is_array($data['location'])) {
            $location = Location::fromArray($data['location']);
        }

        return new self(
            id: $id,
            from: $from,
            query: $query,
            offset: $offset,
            chatType: $chatType,
            location: $location,
        );
    }

    /**
     * Get the unique identifier for this query.
     *
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * Get the sender.
     *
     * @return User
     */
    public function from(): User
    {
        return $this->from;
    }

    /**
     * Get the text of the query.
     *
     * @return string
     */
    public function query(): string
    {
        return $this->query;
    }

    /**
     * Get the offset of the results to be returned.
     *
     * @return string
     */
    public function offset(): string
    {
        return $this->offset;
    }

    /**
     * Get the type of the chat from which the inline query was sent.
     *
     * @return string|null
     */
    public function chatType(): ?string
    {
        return $this->chatType;
    }

    /**
     * Get the sender location.
     *
     * @return Location|null
     */
    public function location(): ?Location
    {
        return $this->location;
    }

    /**
     * Check if the query is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return trim($this->query) === '';
    }

    /**
     * Check if the query has location information.
     *
     * @return bool
     */
    public function hasLocation(): bool
    {
        return $this->location !== null;
    }

    /**
     * Check if the query has a specific chat type.
     *
     * @return bool
     */
    public function hasChatType(): bool
    {
        return $this->chatType !== null;
    }

    /**
     * Get the length of the query string.
     *
     * @return int
     */
    public function queryLength(): int
    {
        return strlen($this->query);
    }

    /**
     * Convert the InlineQuery to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'from' => $this->from->toArray(),
            'query' => $this->query,
            'offset' => $this->offset,
            'chat_type' => $this->chatType,
            'location' => $this->location?->toArray(),
        ], fn ($value) => $value !== null);
    }
}
