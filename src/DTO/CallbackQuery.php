<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: src/DTO/CallbackQuery.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-06
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Support\Collection;
use Telegram\Objects\Support\Validator;

/**
 * Represents an incoming callback query from a callback button in an inline keyboard.
 *
 * This object represents an incoming callback query from a callback button in an inline keyboard.
 * If the button that originated the query was attached to a message sent by the bot, the field message will be present.
 * If the button was attached to a message sent via the bot (in inline mode), the field inline_message_id will be present.
 */
class CallbackQuery implements ArrayableInterface, SerializableInterface
{
    /**
     * @param string $id Unique identifier for this query
     * @param User $from Sender
     * @param Message|null $message Message with the callback button that originated the query
     * @param string|null $inlineMessageId Identifier of the message sent via the bot in inline mode
     * @param string $chatInstance Global identifier, uniquely corresponding to the chat to which the message with the callback button was sent
     * @param string $data Data associated with the callback button
     * @param string|null $gameShortName Short name of a Game to be returned, serves as the unique identifier for the game
     */
    private function __construct(
        private readonly string $id,
        private readonly User $from,
        private readonly ?Message $message = null,
        private readonly ?string $inlineMessageId = null,
        private readonly string $chatInstance = '',
        private readonly string $data = '',
        private readonly ?string $gameShortName = null,
    ) {
    }

    /**
     * Create a CallbackQuery instance from array data.
     *
     * @param array<string, mixed> $data The callback query data from Telegram API
     * @return self
     */
    public static function fromArray(array $data): self
    {
        Validator::requireField($data, 'id', 'CallbackQuery');
        Validator::requireField($data, 'from', 'CallbackQuery');

        $id = Validator::getValue($data, 'id', null, 'string');

        $fromData = Validator::getValue($data, 'from', null, 'array');
        $from = User::fromArray($fromData);

        $message = null;
        if (isset($data['message']) && is_array($data['message'])) {
            $message = Message::fromArray($data['message']);
        }

        $inlineMessageId = Validator::getValue($data, 'inline_message_id', null, 'string');
        $chatInstance = Validator::getValue($data, 'chat_instance', '', 'string');
        $callbackData = Validator::getValue($data, 'data', '', 'string');
        $gameShortName = Validator::getValue($data, 'game_short_name', null, 'string');

        return new self(
            id: $id,
            from: $from,
            message: $message,
            inlineMessageId: $inlineMessageId,
            chatInstance: $chatInstance,
            data: $callbackData,
            gameShortName: $gameShortName,
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
     * Get the message with the callback button that originated the query.
     *
     * @return Message|null
     */
    public function message(): ?Message
    {
        return $this->message;
    }

    /**
     * Get the identifier of the message sent via the bot in inline mode.
     *
     * @return string|null
     */
    public function inlineMessageId(): ?string
    {
        return $this->inlineMessageId;
    }

    /**
     * Get the global identifier, uniquely corresponding to the chat.
     *
     * @return string
     */
    public function chatInstance(): string
    {
        return $this->chatInstance;
    }

    /**
     * Get the data associated with the callback button.
     *
     * @return string
     */
    public function data(): string
    {
        return $this->data;
    }

    /**
     * Get the short name of a Game to be returned.
     *
     * @return string|null
     */
    public function gameShortName(): ?string
    {
        return $this->gameShortName;
    }

    /**
     * Check if this callback query has a message.
     *
     * @return bool
     */
    public function hasMessage(): bool
    {
        return $this->message !== null;
    }

    /**
     * Check if this callback query is from an inline message.
     *
     * @return bool
     */
    public function isInlineMessage(): bool
    {
        return $this->inlineMessageId !== null;
    }

    /**
     * Check if this callback query is for a game.
     *
     * @return bool
     */
    public function isGame(): bool
    {
        return $this->gameShortName !== null;
    }

    /**
     * Parse the callback data as key-value pairs.
     * Assumes data format like "key1:value1;key2:value2"
     *
     * @return Collection<string, string>
     */
    public function parsedData(): Collection
    {
        if ($this->data === '') {
            return Collection::make([]);
        }

        $pairs = explode(';', $this->data);
        $parsed = [];

        foreach ($pairs as $pair) {
            if (str_contains($pair, ':')) {
                [$key, $value] = explode(':', $pair, 2);
                $parsed[trim($key)] = trim($value);
            }
        }

        return Collection::make($parsed);
    }

    /**
     * Convert the CallbackQuery to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'from' => $this->from->toArray(),
            'message' => $this->message?->toArray(),
            'inline_message_id' => $this->inlineMessageId,
            'chat_instance' => $this->chatInstance !== '' ? $this->chatInstance : null,
            'data' => $this->data !== '' ? $this->data : null,
            'game_short_name' => $this->gameShortName,
        ], fn ($value) => $value !== null);
    }
}
