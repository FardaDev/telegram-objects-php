<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/src/DTO/TelegramUpdate.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-06
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Support\Validator;

/**
 * Represents an incoming update from Telegram.
 *
 * This object represents an incoming update. At most one of the optional
 * parameters can be present in any given update.
 */
class TelegramUpdate implements ArrayableInterface, SerializableInterface
{
    /**
     * @param int $id The update's unique identifier
     * @param Message|null $message New incoming message of any kind — text, photo, sticker, etc.
     */
    private function __construct(
        private readonly int $id,
        private readonly ?Message $message = null,
        // TODO: Add more update types as we implement their DTOs:
        // private readonly ?CallbackQuery $callbackQuery = null,
        // private readonly ?InlineQuery $inlineQuery = null,
        // private readonly ?Poll $poll = null,
        // private readonly ?PollAnswer $pollAnswer = null,
        // etc.
    ) {
    }

    /**
     * Create a TelegramUpdate instance from array data.
     *
     * @param array<string, mixed> $data The update data from Telegram API
     * @return self
     */
    public static function fromArray(array $data): self
    {
        Validator::requireField($data, 'update_id', 'TelegramUpdate');

        $id = Validator::getValue($data, 'update_id', null, 'int');

        // Handle message (including edited_message, channel_post, edited_channel_post)
        $message = null;
        if (isset($data['message']) && is_array($data['message'])) {
            $message = Message::fromArray($data['message']);
        } elseif (isset($data['edited_message']) && is_array($data['edited_message'])) {
            $message = Message::fromArray($data['edited_message']);
        } elseif (isset($data['channel_post']) && is_array($data['channel_post'])) {
            $message = Message::fromArray($data['channel_post']);
        } elseif (isset($data['edited_channel_post']) && is_array($data['edited_channel_post'])) {
            $message = Message::fromArray($data['edited_channel_post']);
        }

        // TODO: Handle other update types as we implement their DTOs:
        // - callback_query
        // - inline_query
        // - poll
        // - poll_answer
        // - chat_member
        // - my_chat_member
        // - pre_checkout_query
        // - message_reaction
        // etc.

        return new self(
            id: $id,
            message: $message,
        );
    }

    /**
     * Get the update's unique identifier.
     *
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }

    /**
     * Get the new incoming message of any kind.
     *
     * @return Message|null
     */
    public function message(): ?Message
    {
        return $this->message;
    }

    /**
     * Check if this update contains a message.
     *
     * @return bool
     */
    public function hasMessage(): bool
    {
        return $this->message !== null;
    }

    /**
     * Get the update type.
     *
     * @return string
     */
    public function getType(): string
    {
        if ($this->message !== null) {
            return 'message';
        }

        // TODO: Add more update type detection as we implement other DTOs

        return 'unknown';
    }

    /**
     * Convert the TelegramUpdate to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'update_id' => $this->id,
            'message' => $this->message?->toArray(),
            // TODO: Add other update types as we implement them
        ], fn ($value) => $value !== null);
    }
}
