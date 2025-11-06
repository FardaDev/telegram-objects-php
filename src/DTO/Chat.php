<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/src/DTO/Chat.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-06
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Support\Validator;

/**
 * Represents a Telegram chat.
 *
 * This object represents a chat.
 */
class Chat implements ArrayableInterface, SerializableInterface
{
    /**
     * Chat type constants.
     */
    public const TYPE_SENDER = 'sender';
    public const TYPE_PRIVATE = 'private';
    public const TYPE_GROUP = 'group';
    public const TYPE_SUPERGROUP = 'supergroup';
    public const TYPE_CHANNEL = 'channel';

    /**
     * @param string $id Unique identifier for this chat
     * @param string $type Type of chat
     * @param string|null $title Title, for supergroups, channels and group chats
     * @param string|null $username Username, for private chats, supergroups and channels if available
     * @param string|null $firstName First name of the other party in a private chat
     * @param string|null $lastName Last name of the other party in a private chat
     * @param bool $isForum True, if the supergroup chat is a forum
     * @param bool $isDirectMessages True, if the chat is a direct message chat
     */
    private function __construct(
        private readonly string $id,
        private readonly string $type,
        private readonly ?string $title = null,
        private readonly ?string $username = null,
        private readonly ?string $firstName = null,
        private readonly ?string $lastName = null,
        private readonly bool $isForum = false,
        private readonly bool $isDirectMessages = false,
    ) {
    }

    /**
     * Create a Chat instance from array data.
     *
     * @param array<string, mixed> $data The chat data from Telegram API
     * @return self
     */
    public static function fromArray(array $data): self
    {
        Validator::requireField($data, 'id', 'Chat');
        Validator::requireField($data, 'type', 'Chat');

        $id = Validator::getValue($data, 'id', null, 'string');
        $type = Validator::getValue($data, 'type', null, 'string');

        // Validate chat type
        $allowedTypes = [
            self::TYPE_SENDER,
            self::TYPE_PRIVATE,
            self::TYPE_GROUP,
            self::TYPE_SUPERGROUP,
            self::TYPE_CHANNEL,
        ];
        Validator::validateEnum($type, $allowedTypes, 'chat type');

        $title = Validator::getValue($data, 'title', null, 'string');
        $username = Validator::getValue($data, 'username', null, 'string');
        $firstName = Validator::getValue($data, 'first_name', null, 'string');
        $lastName = Validator::getValue($data, 'last_name', null, 'string');
        $isForum = Validator::getValue($data, 'is_forum', false, 'bool');
        $isDirectMessages = Validator::getValue($data, 'is_direct_messages', false, 'bool');

        return new self(
            id: $id,
            type: $type,
            title: $title,
            username: $username,
            firstName: $firstName,
            lastName: $lastName,
            isForum: $isForum,
            isDirectMessages: $isDirectMessages,
        );
    }

    /**
     * Get the unique identifier for this chat.
     *
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * Get the type of chat.
     *
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * Get the title for supergroups, channels and group chats.
     *
     * @return string|null
     */
    public function title(): ?string
    {
        return $this->title;
    }

    /**
     * Get the username for private chats, supergroups and channels if available.
     *
     * @return string|null
     */
    public function username(): ?string
    {
        return $this->username;
    }

    /**
     * Get the first name of the other party in a private chat.
     *
     * @return string|null
     */
    public function firstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * Get the last name of the other party in a private chat.
     *
     * @return string|null
     */
    public function lastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * Check if the supergroup chat is a forum.
     *
     * @return bool
     */
    public function isForum(): bool
    {
        return $this->isForum;
    }

    /**
     * Check if the chat is a direct message chat.
     *
     * @return bool
     */
    public function isDirectMessages(): bool
    {
        return $this->isDirectMessages;
    }

    /**
     * Check if this is a private chat.
     *
     * @return bool
     */
    public function isPrivate(): bool
    {
        return $this->type === self::TYPE_PRIVATE;
    }

    /**
     * Check if this is a group chat.
     *
     * @return bool
     */
    public function isGroup(): bool
    {
        return in_array($this->type, [self::TYPE_GROUP, self::TYPE_SUPERGROUP], true);
    }

    /**
     * Check if this is a channel.
     *
     * @return bool
     */
    public function isChannel(): bool
    {
        return $this->type === self::TYPE_CHANNEL;
    }

    /**
     * Get the display name for this chat.
     *
     * @return string
     */
    public function displayName(): string
    {
        if ($this->title !== null) {
            return $this->title;
        }

        if ($this->firstName !== null) {
            $parts = array_filter([$this->firstName, $this->lastName]);

            return implode(' ', $parts);
        }

        if ($this->username !== null) {
            return '@' . $this->username;
        }

        return 'Chat ' . $this->id;
    }

    /**
     * Convert the Chat to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'type' => $this->type,
            'title' => $this->title,
            'username' => $this->username,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'is_forum' => $this->isForum,
            'is_direct_messages' => $this->isDirectMessages,
        ], fn ($value) => $value !== null && $value !== '');
    }
}
