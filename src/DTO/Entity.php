<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: src/DTO/Entity.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-06
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Support\Validator;

/**
 * Represents one special entity in a text message.
 *
 * This object represents one special entity in a text message. For example, hashtags, usernames, URLs, etc.
 */
class Entity implements ArrayableInterface, SerializableInterface
{
    /**
     * @param string $type Type of the entity
     * @param int $offset Offset in UTF-16 code units to the start of the entity
     * @param int $length Length of the entity in UTF-16 code units
     * @param string|null $url For "text_link" only, URL that will be opened after user taps on the text
     * @param User|null $user For "text_mention" only, the mentioned user
     * @param string|null $language For "pre" only, the programming language of the entity text
     * @param string|null $customEmojiId For "custom_emoji" only, unique identifier of the custom emoji
     */
    private function __construct(
        private readonly string $type,
        private readonly int $offset,
        private readonly int $length,
        private readonly ?string $url = null,
        private readonly ?User $user = null,
        private readonly ?string $language = null,
        private readonly ?string $customEmojiId = null,
    ) {
    }

    /**
     * Create an Entity instance from array data.
     *
     * @param array<string, mixed> $data The entity data from Telegram API
     * @return self
     */
    public static function fromArray(array $data): self
    {
        Validator::requireField($data, 'type', 'Entity');
        Validator::requireField($data, 'offset', 'Entity');
        Validator::requireField($data, 'length', 'Entity');

        $type = Validator::getValue($data, 'type', null, 'string');
        $offset = Validator::getValue($data, 'offset', null, 'int');
        $length = Validator::getValue($data, 'length', null, 'int');
        $url = Validator::getValue($data, 'url', null, 'string');
        $language = Validator::getValue($data, 'language', null, 'string');
        $customEmojiId = Validator::getValue($data, 'custom_emoji_id', null, 'string');

        // Validate offset and length are non-negative
        Validator::validateRange($offset, 'offset', 0);
        Validator::validateRange($length, 'length', 1);

        $user = null;
        if (isset($data['user']) && is_array($data['user'])) {
            $user = User::fromArray($data['user']);
        }

        return new self(
            type: $type,
            offset: $offset,
            length: $length,
            url: $url,
            user: $user,
            language: $language,
            customEmojiId: $customEmojiId,
        );
    }

    /**
     * Get the type of the entity.
     *
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * Get the offset in UTF-16 code units to the start of the entity.
     *
     * @return int
     */
    public function offset(): int
    {
        return $this->offset;
    }

    /**
     * Get the length of the entity in UTF-16 code units.
     *
     * @return int
     */
    public function length(): int
    {
        return $this->length;
    }

    /**
     * Get the URL that will be opened after user taps on the text.
     *
     * @return string|null
     */
    public function url(): ?string
    {
        return $this->url;
    }

    /**
     * Get the mentioned user.
     *
     * @return User|null
     */
    public function user(): ?User
    {
        return $this->user;
    }

    /**
     * Get the programming language of the entity text.
     *
     * @return string|null
     */
    public function language(): ?string
    {
        return $this->language;
    }

    /**
     * Get the unique identifier of the custom emoji.
     *
     * @return string|null
     */
    public function customEmojiId(): ?string
    {
        return $this->customEmojiId;
    }

    /**
     * Check if this is a text link entity.
     *
     * @return bool
     */
    public function isTextLink(): bool
    {
        return $this->type === 'text_link';
    }

    /**
     * Check if this is a text mention entity.
     *
     * @return bool
     */
    public function isTextMention(): bool
    {
        return $this->type === 'text_mention';
    }

    /**
     * Check if this is a pre-formatted code entity.
     *
     * @return bool
     */
    public function isPreformatted(): bool
    {
        return $this->type === 'pre';
    }

    /**
     * Check if this is a custom emoji entity.
     *
     * @return bool
     */
    public function isCustomEmoji(): bool
    {
        return $this->type === 'custom_emoji';
    }

    /**
     * Check if this is a URL entity.
     *
     * @return bool
     */
    public function isUrl(): bool
    {
        return $this->type === 'url';
    }

    /**
     * Check if this is a hashtag entity.
     *
     * @return bool
     */
    public function isHashtag(): bool
    {
        return $this->type === 'hashtag';
    }

    /**
     * Check if this is a mention entity.
     *
     * @return bool
     */
    public function isMention(): bool
    {
        return $this->type === 'mention';
    }

    /**
     * Convert the Entity to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'type' => $this->type,
            'offset' => $this->offset,
            'length' => $this->length,
            'url' => $this->url,
            'user' => $this->user?->toArray(),
            'language' => $this->language,
            'custom_emoji_id' => $this->customEmojiId,
        ], fn ($value) => $value !== null);
    }
}
