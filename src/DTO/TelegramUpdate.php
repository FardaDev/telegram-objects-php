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
     * @param CallbackQuery|null $callbackQuery New incoming callback query
     * @param InlineQuery|null $inlineQuery New incoming inline query
     * @param Poll|null $poll New poll state
     * @param PollAnswer|null $pollAnswer User changed their answer in a non-anonymous poll
     * @param ChatMemberUpdate|null $chatMemberUpdate Chat member's status was updated
     * @param ChatMemberUpdate|null $botChatStatusChange Bot's chat member status was updated
     * @param PreCheckoutQuery|null $preCheckoutQuery New incoming pre-checkout query
     * @param ChatJoinRequest|null $chatJoinRequest Request to join the chat has been sent
     * @param Reaction|null $messageReaction A reaction to a message was changed by a user
     */
    private function __construct(
        private readonly int $id,
        private readonly ?Message $message = null,
        private readonly ?CallbackQuery $callbackQuery = null,
        private readonly ?InlineQuery $inlineQuery = null,
        private readonly ?Poll $poll = null,
        private readonly ?PollAnswer $pollAnswer = null,
        private readonly ?ChatMemberUpdate $chatMemberUpdate = null,
        private readonly ?ChatMemberUpdate $botChatStatusChange = null,
        private readonly ?PreCheckoutQuery $preCheckoutQuery = null,
        private readonly ?ChatJoinRequest $chatJoinRequest = null,
        private readonly ?Reaction $messageReaction = null,
    ) {
    }

    /**
     * Create a TelegramUpdate instance from array data.
     *
     * @param array<string, mixed> $data The update data from Telegram API
     * @return self
     * @throws \Telegram\Objects\Exceptions\ValidationException If required fields are missing or invalid
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

        // Handle callback query
        $callbackQuery = null;
        if (isset($data['callback_query']) && is_array($data['callback_query'])) {
            $callbackQuery = CallbackQuery::fromArray($data['callback_query']);
        }

        // Handle inline query
        $inlineQuery = null;
        if (isset($data['inline_query']) && is_array($data['inline_query'])) {
            $inlineQuery = InlineQuery::fromArray($data['inline_query']);
        }

        // Handle poll
        $poll = null;
        if (isset($data['poll']) && is_array($data['poll'])) {
            $poll = Poll::fromArray($data['poll']);
        }

        // Handle poll answer
        $pollAnswer = null;
        if (isset($data['poll_answer']) && is_array($data['poll_answer'])) {
            $pollAnswer = PollAnswer::fromArray($data['poll_answer']);
        }

        // Handle chat member update
        $chatMemberUpdate = null;
        if (isset($data['chat_member']) && is_array($data['chat_member'])) {
            $chatMemberUpdate = ChatMemberUpdate::fromArray($data['chat_member']);
        }

        // Handle bot chat status change
        $botChatStatusChange = null;
        if (isset($data['my_chat_member']) && is_array($data['my_chat_member'])) {
            $botChatStatusChange = ChatMemberUpdate::fromArray($data['my_chat_member']);
        }

        // Handle pre-checkout query
        $preCheckoutQuery = null;
        if (isset($data['pre_checkout_query']) && is_array($data['pre_checkout_query'])) {
            $preCheckoutQuery = PreCheckoutQuery::fromArray($data['pre_checkout_query']);
        }

        // Handle chat join request
        $chatJoinRequest = null;
        if (isset($data['chat_join_request']) && is_array($data['chat_join_request'])) {
            $chatJoinRequest = ChatJoinRequest::fromArray($data['chat_join_request']);
        }

        // Handle message reaction
        $messageReaction = null;
        if (isset($data['message_reaction']) && is_array($data['message_reaction'])) {
            $messageReaction = Reaction::fromArray($data['message_reaction']);
        }

        return new self(
            id: $id,
            message: $message,
            callbackQuery: $callbackQuery,
            inlineQuery: $inlineQuery,
            poll: $poll,
            pollAnswer: $pollAnswer,
            chatMemberUpdate: $chatMemberUpdate,
            botChatStatusChange: $botChatStatusChange,
            preCheckoutQuery: $preCheckoutQuery,
            chatJoinRequest: $chatJoinRequest,
            messageReaction: $messageReaction,
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
     * Get the callback query.
     *
     * @return CallbackQuery|null
     */
    public function callbackQuery(): ?CallbackQuery
    {
        return $this->callbackQuery;
    }

    /**
     * Check if this update contains a callback query.
     *
     * @return bool
     */
    public function hasCallbackQuery(): bool
    {
        return $this->callbackQuery !== null;
    }

    /**
     * Get the inline query.
     *
     * @return InlineQuery|null
     */
    public function inlineQuery(): ?InlineQuery
    {
        return $this->inlineQuery;
    }

    /**
     * Check if this update contains an inline query.
     *
     * @return bool
     */
    public function hasInlineQuery(): bool
    {
        return $this->inlineQuery !== null;
    }

    /**
     * Get the poll.
     *
     * @return Poll|null
     */
    public function poll(): ?Poll
    {
        return $this->poll;
    }

    /**
     * Check if this update contains a poll.
     *
     * @return bool
     */
    public function hasPoll(): bool
    {
        return $this->poll !== null;
    }

    /**
     * Get the poll answer.
     *
     * @return PollAnswer|null
     */
    public function pollAnswer(): ?PollAnswer
    {
        return $this->pollAnswer;
    }

    /**
     * Check if this update contains a poll answer.
     *
     * @return bool
     */
    public function hasPollAnswer(): bool
    {
        return $this->pollAnswer !== null;
    }

    /**
     * Get the chat member update.
     *
     * @return ChatMemberUpdate|null
     */
    public function chatMemberUpdate(): ?ChatMemberUpdate
    {
        return $this->chatMemberUpdate;
    }

    /**
     * Check if this update contains a chat member update.
     *
     * @return bool
     */
    public function hasChatMemberUpdate(): bool
    {
        return $this->chatMemberUpdate !== null;
    }

    /**
     * Get the bot chat status change.
     *
     * @return ChatMemberUpdate|null
     */
    public function botChatStatusChange(): ?ChatMemberUpdate
    {
        return $this->botChatStatusChange;
    }

    /**
     * Check if this update contains a bot chat status change.
     *
     * @return bool
     */
    public function hasBotChatStatusChange(): bool
    {
        return $this->botChatStatusChange !== null;
    }

    /**
     * Get the pre-checkout query.
     *
     * @return PreCheckoutQuery|null
     */
    public function preCheckoutQuery(): ?PreCheckoutQuery
    {
        return $this->preCheckoutQuery;
    }

    /**
     * Check if this update contains a pre-checkout query.
     *
     * @return bool
     */
    public function hasPreCheckoutQuery(): bool
    {
        return $this->preCheckoutQuery !== null;
    }

    /**
     * Get the chat join request.
     *
     * @return ChatJoinRequest|null
     */
    public function chatJoinRequest(): ?ChatJoinRequest
    {
        return $this->chatJoinRequest;
    }

    /**
     * Check if this update contains a chat join request.
     *
     * @return bool
     */
    public function hasChatJoinRequest(): bool
    {
        return $this->chatJoinRequest !== null;
    }

    /**
     * Get the message reaction.
     *
     * @return Reaction|null
     */
    public function messageReaction(): ?Reaction
    {
        return $this->messageReaction;
    }

    /**
     * Check if this update contains a message reaction.
     *
     * @return bool
     */
    public function hasMessageReaction(): bool
    {
        return $this->messageReaction !== null;
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
        if ($this->callbackQuery !== null) {
            return 'callback_query';
        }
        if ($this->inlineQuery !== null) {
            return 'inline_query';
        }
        if ($this->poll !== null) {
            return 'poll';
        }
        if ($this->pollAnswer !== null) {
            return 'poll_answer';
        }
        if ($this->chatMemberUpdate !== null) {
            return 'chat_member';
        }
        if ($this->botChatStatusChange !== null) {
            return 'my_chat_member';
        }
        if ($this->preCheckoutQuery !== null) {
            return 'pre_checkout_query';
        }
        if ($this->chatJoinRequest !== null) {
            return 'chat_join_request';
        }
        if ($this->messageReaction !== null) {
            return 'message_reaction';
        }

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
            'callback_query' => $this->callbackQuery?->toArray(),
            'inline_query' => $this->inlineQuery?->toArray(),
            'poll' => $this->poll?->toArray(),
            'poll_answer' => $this->pollAnswer?->toArray(),
            'chat_member' => $this->chatMemberUpdate?->toArray(),
            'my_chat_member' => $this->botChatStatusChange?->toArray(),
            'pre_checkout_query' => $this->preCheckoutQuery?->toArray(),
            'chat_join_request' => $this->chatJoinRequest?->toArray(),
            'message_reaction' => $this->messageReaction?->toArray(),
        ], fn ($value) => $value !== null);
    }
}
