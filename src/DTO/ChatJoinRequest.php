<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: src/DTO/ChatJoinRequest.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-07
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Support\TelegramDateTime;
use Telegram\Objects\Support\Validator;

/**
 * Represents a join request sent to a chat.
 *
 * This class contains information about a user's request to join a chat,
 * including the user details, request date, and associated invite link.
 */
class ChatJoinRequest implements ArrayableInterface, SerializableInterface
{
    private int $userChatId;
    private TelegramDateTime $date;
    private ?string $bio = null;
    private ?ChatInviteLink $inviteLink = null;
    private Chat $chat;
    private User $from;

    private function __construct()
    {
    }

    /**
     * Create a ChatJoinRequest instance from an array of data.
     *
     * @param array<string, mixed> $data The chat join request data
     * @return self
     * @throws \Telegram\Objects\Exceptions\ValidationException If required fields are missing or invalid
     */
    public static function fromArray(array $data): self
    {
        $request = new self();

        Validator::requireField($data, 'user_chat_id', 'ChatJoinRequest');
        Validator::requireField($data, 'date', 'ChatJoinRequest');
        Validator::requireField($data, 'chat', 'ChatJoinRequest');
        Validator::requireField($data, 'from', 'ChatJoinRequest');

        $userChatId = Validator::getValue($data, 'user_chat_id', null, 'int');
        $date = Validator::getValue($data, 'date', null, 'int');
        $chatData = Validator::getValue($data, 'chat', null, 'array');
        $fromData = Validator::getValue($data, 'from', null, 'array');

        $request->userChatId = $userChatId;
        $request->date = TelegramDateTime::fromTimestamp($date);
        $request->chat = Chat::fromArray($chatData);
        $request->from = User::fromArray($fromData);

        $request->bio = Validator::getValue($data, 'bio', null, 'string');

        $inviteLinkData = Validator::getValue($data, 'invite_link', null, 'array');
        if ($inviteLinkData !== null) {
            $request->inviteLink = ChatInviteLink::fromArray($inviteLinkData);
        }

        return $request;
    }

    /**
     * Get the user chat ID.
     */
    public function userChatId(): int
    {
        return $this->userChatId;
    }

    /**
     * Get the date when the request was sent.
     */
    public function date(): TelegramDateTime
    {
        return $this->date;
    }

    /**
     * Get the user's bio.
     */
    public function bio(): ?string
    {
        return $this->bio;
    }

    /**
     * Get the invite link used for the request.
     */
    public function inviteLink(): ?ChatInviteLink
    {
        return $this->inviteLink;
    }

    /**
     * Get the chat the user wants to join.
     */
    public function chat(): Chat
    {
        return $this->chat;
    }

    /**
     * Get the user who sent the request.
     */
    public function from(): User
    {
        return $this->from;
    }

    /**
     * Check if the request has a bio.
     */
    public function hasBio(): bool
    {
        return $this->bio !== null && $this->bio !== '';
    }

    /**
     * Check if the request was made via an invite link.
     */
    public function hasInviteLink(): bool
    {
        return $this->inviteLink !== null;
    }

    /**
     * Get the age of the request in seconds.
     */
    public function ageInSeconds(): int
    {
        return time() - $this->date->getTimestamp();
    }

    /**
     * Check if the request is recent (within the last hour).
     */
    public function isRecent(): bool
    {
        return $this->ageInSeconds() < 3600; // 1 hour
    }

    /**
     * Get a formatted string representation of the request age.
     */
    public function getAgeString(): string
    {
        $age = $this->ageInSeconds();

        if ($age < 60) {
            return $age . ' second' . ($age !== 1 ? 's' : '') . ' ago';
        }

        $minutes = intval($age / 60);
        if ($minutes < 60) {
            return $minutes . ' minute' . ($minutes !== 1 ? 's' : '') . ' ago';
        }

        $hours = intval($minutes / 60);
        if ($hours < 24) {
            return $hours . ' hour' . ($hours !== 1 ? 's' : '') . ' ago';
        }

        $days = intval($hours / 24);

        return $days . ' day' . ($days !== 1 ? 's' : '') . ' ago';
    }

    /**
     * Convert the chat join request to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'user_chat_id' => $this->userChatId,
            'date' => $this->date->getTimestamp(),
            'bio' => $this->bio,
            'invite_link' => $this->inviteLink?->toArray(),
            'chat' => $this->chat->toArray(),
            'from' => $this->from->toArray(),
        ], fn ($value) => $value !== null);
    }
}
