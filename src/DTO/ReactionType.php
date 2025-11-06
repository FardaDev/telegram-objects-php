<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/src/DTO/ReactionType.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-07
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Support\Validator;

/**
 * Represents a reaction type.
 *
 * This class describes the type of a reaction to a message.
 * Currently, it can be one of: emoji, custom_emoji, paid.
 */
class ReactionType implements ArrayableInterface, SerializableInterface
{
    // Reaction type constants
    public const TYPE_EMOJI = 'emoji';
    public const TYPE_CUSTOM_EMOJI = 'custom_emoji';
    public const TYPE_PAID_EMOJI = 'paid';

    private string $type;
    private ?string $emoji = null;
    private ?string $customEmojiId = null;

    private function __construct()
    {
    }

    /**
     * Create a ReactionType instance from an array of data.
     *
     * @param array<string, mixed> $data The reaction type data
     * @return self
     * @throws \Telegram\Objects\Exceptions\ValidationException If required fields are missing or invalid
     */
    public static function fromArray(array $data): self
    {
        Validator::requireField($data, 'type', 'ReactionType');

        $type = Validator::getValue($data, 'type', null, 'string');
        $emoji = Validator::getValue($data, 'emoji', null, 'string');
        $customEmojiId = Validator::getValue($data, 'custom_emoji_id', null, 'string');

        // Validate reaction type
        $validTypes = [self::TYPE_EMOJI, self::TYPE_CUSTOM_EMOJI, self::TYPE_PAID_EMOJI];
        Validator::validateEnum($type, $validTypes, 'type');

        $reaction = new self();

        $reaction->type = $type;
        $reaction->emoji = $emoji;
        $reaction->customEmojiId = $customEmojiId;

        return $reaction;
    }

    /**
     * Get the reaction type.
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * Get the emoji (for emoji type reactions).
     */
    public function emoji(): ?string
    {
        return $this->emoji;
    }

    /**
     * Get the custom emoji ID (for custom emoji type reactions).
     */
    public function customEmojiId(): ?string
    {
        return $this->customEmojiId;
    }

    /**
     * Check if this is an emoji reaction.
     */
    public function isEmoji(): bool
    {
        return $this->type === self::TYPE_EMOJI;
    }

    /**
     * Check if this is a custom emoji reaction.
     */
    public function isCustomEmoji(): bool
    {
        return $this->type === self::TYPE_CUSTOM_EMOJI;
    }

    /**
     * Check if this is a paid emoji reaction.
     */
    public function isPaidEmoji(): bool
    {
        return $this->type === self::TYPE_PAID_EMOJI;
    }

    /**
     * Get a display representation of the reaction.
     */
    public function getDisplayValue(): string
    {
        return match ($this->type) {
            self::TYPE_EMOJI => $this->emoji ?? 'Unknown emoji',
            self::TYPE_CUSTOM_EMOJI => "Custom emoji: {$this->customEmojiId}",
            self::TYPE_PAID_EMOJI => 'Paid reaction',
            default => 'Unknown reaction type',
        };
    }

    /**
     * Convert the reaction type to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'type' => $this->type,
            'emoji' => $this->emoji,
            'custom_emoji_id' => $this->customEmojiId,
        ], fn ($value) => $value !== null);
    }
}
