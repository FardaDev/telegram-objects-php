<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/src/DTO/ChatMemberUpdate.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-07
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Support\TelegramDateTime;
use Telegram\Objects\Support\Validator;

/**
 * Represents a change in a chat member's status.
 *
 * This class contains information about changes to a user's membership status
 * in a chat, including the previous and new status, and related information.
 */
class ChatMemberUpdate implements ArrayableInterface, SerializableInterface
{
    private TelegramDateTime $date;
    private Chat $chat;
    private User $from;
    private ChatMember $previous;
    private ChatMember $new;
    private ?ChatInviteLink $inviteLink = null;
    private ?bool $viaJoinRequest = null;
    private ?bool $viaChatFolderInviteLink = null;

    private function __construct()
    {
    }

    /**
     * Create a ChatMemberUpdate instance from an array of data.
     *
     * @param array<string, mixed> $data The chat member update data
     * @return self
     * @throws \Telegram\Objects\Exceptions\ValidationException If required fields are missing or invalid
     */
    public static function fromArray(array $data): self
    {
        $chatMemberUpdate = new self();

        Validator::requireField($data, 'date', 'ChatMemberUpdate');
        Validator::requireField($data, 'chat', 'ChatMemberUpdate');
        Validator::requireField($data, 'from', 'ChatMemberUpdate');
        Validator::requireField($data, 'old_chat_member', 'ChatMemberUpdate');
        Validator::requireField($data, 'new_chat_member', 'ChatMemberUpdate');

        $date = Validator::getValue($data, 'date', null, 'int');
        $chatData = Validator::getValue($data, 'chat', null, 'array');
        $fromData = Validator::getValue($data, 'from', null, 'array');
        $oldChatMemberData = Validator::getValue($data, 'old_chat_member', null, 'array');
        $newChatMemberData = Validator::getValue($data, 'new_chat_member', null, 'array');

        $chatMemberUpdate->date = TelegramDateTime::fromTimestamp($date);
        $chatMemberUpdate->chat = Chat::fromArray($chatData);
        $chatMemberUpdate->from = User::fromArray($fromData);
        $chatMemberUpdate->previous = ChatMember::fromArray($oldChatMemberData);
        $chatMemberUpdate->new = ChatMember::fromArray($newChatMemberData);

        $inviteLinkData = Validator::getValue($data, 'invite_link', null, 'array');
        if ($inviteLinkData !== null) {
            $chatMemberUpdate->inviteLink = ChatInviteLink::fromArray($inviteLinkData);
        }

        $chatMemberUpdate->viaJoinRequest = Validator::getValue($data, 'via_join_request', null, 'bool');
        $chatMemberUpdate->viaChatFolderInviteLink = Validator::getValue($data, 'via_chat_folder_invite_link', null, 'bool');

        return $chatMemberUpdate;
    }

    /**
     * Get the date when the change occurred.
     */
    public function date(): TelegramDateTime
    {
        return $this->date;
    }

    /**
     * Get the chat where the change occurred.
     */
    public function chat(): Chat
    {
        return $this->chat;
    }

    /**
     * Get the user who performed the change.
     */
    public function from(): User
    {
        return $this->from;
    }

    /**
     * Get the previous chat member status.
     */
    public function previous(): ChatMember
    {
        return $this->previous;
    }

    /**
     * Get the new chat member status.
     */
    public function new(): ChatMember
    {
        return $this->new;
    }

    /**
     * Get the invite link used for the change (if any).
     */
    public function inviteLink(): ?ChatInviteLink
    {
        return $this->inviteLink;
    }

    /**
     * Check if the change was via a join request.
     */
    public function viaJoinRequest(): ?bool
    {
        return $this->viaJoinRequest;
    }

    /**
     * Check if the change was via a chat folder invite link.
     */
    public function viaChatFolderInviteLink(): ?bool
    {
        return $this->viaChatFolderInviteLink;
    }

    /**
     * Check if this is a promotion (member status improved).
     */
    public function isPromotion(): bool
    {
        $statusHierarchy = [
            ChatMember::STATUS_KICKED => 0,
            ChatMember::STATUS_LEFT => 1,
            ChatMember::STATUS_RESTRICTED => 2,
            ChatMember::STATUS_MEMBER => 3,
            ChatMember::STATUS_ADMINISTRATOR => 4,
            ChatMember::STATUS_CREATOR => 5,
        ];

        $previousLevel = $statusHierarchy[$this->previous->status()] ?? 0;
        $newLevel = $statusHierarchy[$this->new->status()] ?? 0;

        return $newLevel > $previousLevel;
    }

    /**
     * Check if this is a demotion (member status decreased).
     */
    public function isDemotion(): bool
    {
        $statusHierarchy = [
            ChatMember::STATUS_KICKED => 0,
            ChatMember::STATUS_LEFT => 1,
            ChatMember::STATUS_RESTRICTED => 2,
            ChatMember::STATUS_MEMBER => 3,
            ChatMember::STATUS_ADMINISTRATOR => 4,
            ChatMember::STATUS_CREATOR => 5,
        ];

        $previousLevel = $statusHierarchy[$this->previous->status()] ?? 0;
        $newLevel = $statusHierarchy[$this->new->status()] ?? 0;

        return $newLevel < $previousLevel;
    }

    /**
     * Check if the user joined the chat.
     */
    public function isJoin(): bool
    {
        return in_array($this->previous->status(), [ChatMember::STATUS_LEFT, ChatMember::STATUS_KICKED]) &&
               in_array($this->new->status(), [ChatMember::STATUS_MEMBER, ChatMember::STATUS_ADMINISTRATOR, ChatMember::STATUS_CREATOR]);
    }

    /**
     * Check if the user left the chat.
     */
    public function isLeave(): bool
    {
        return in_array($this->previous->status(), [ChatMember::STATUS_MEMBER, ChatMember::STATUS_ADMINISTRATOR, ChatMember::STATUS_CREATOR]) &&
               in_array($this->new->status(), [ChatMember::STATUS_LEFT, ChatMember::STATUS_KICKED]);
    }

    /**
     * Convert the chat member update to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'date' => $this->date->getTimestamp(),
            'chat' => $this->chat->toArray(),
            'from' => $this->from->toArray(),
            'old_chat_member' => $this->previous->toArray(),
            'new_chat_member' => $this->new->toArray(),
            'invite_link' => $this->inviteLink?->toArray(),
            'via_join_request' => $this->viaJoinRequest,
            'via_chat_folder_invite_link' => $this->viaChatFolderInviteLink,
        ], fn ($value) => $value !== null);
    }
}
