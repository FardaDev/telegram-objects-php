<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/src/DTO/ChatMember.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-07
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Exceptions\ValidationException;

/**
 * Represents a chat member with their status and permissions.
 *
 * This class contains information about a user's membership status in a chat,
 * including their role, permissions, and any restrictions or privileges.
 */
class ChatMember implements ArrayableInterface, SerializableInterface
{
    // Chat member status constants
    public const STATUS_CREATOR = 'creator';
    public const STATUS_ADMINISTRATOR = 'administrator';
    public const STATUS_MEMBER = 'member';
    public const STATUS_RESTRICTED = 'restricted';
    public const STATUS_LEFT = 'left';
    public const STATUS_KICKED = 'kicked';

    private string $status;
    private User $user;
    private bool $isAnonymous = false;
    private bool $isMember = false;
    private ?string $customTitle = null;
    private ?int $untilDate = null;

    // Administrative permissions
    private bool $canBeEdited = false;
    private bool $canChangeInfo = false;
    private bool $canInviteUsers = false;
    private bool $canManageChat = false;
    private bool $canManageTopics = false;
    private bool $canManageVideoChats = false;
    private bool $canManageVoiceChats = false;
    private bool $canManageDirectMessages = false;
    private bool $canRestrictMembers = false;
    private bool $canPromoteMembers = false;
    private bool $canPostMessages = false;
    private bool $canEditMessages = false;
    private bool $canDeleteMessages = false;
    private bool $canPinMessages = false;
    private bool $canPostStories = false;
    private bool $canEditStories = false;
    private bool $canDeleteStories = false;

    // User permissions
    private bool $canSendMessages = false;
    private bool $canSendMediaMessages = false;
    private bool $canSendAudios = false;
    private bool $canSendDocuments = false;
    private bool $canSendPhotos = false;
    private bool $canSendVideos = false;
    private bool $canSendVideoNotes = false;
    private bool $canSendVoiceNotes = false;
    private bool $canSendPolls = false;
    private bool $canSendOtherMessages = false;
    private bool $canAddWebPagePreviews = false;

    private function __construct()
    {
    }

    /**
     * Create a ChatMember instance from an array of data.
     *
     * @param array<string, mixed> $data The chat member data
     * @return self
     * @throws ValidationException If required fields are missing or invalid
     */
    public static function fromArray(array $data): self
    {
        if (! isset($data['status'])) {
            throw new ValidationException("Missing required field 'status'");
        }

        if (! isset($data['user']) || ! is_array($data['user'])) {
            throw new ValidationException("Missing or invalid required field 'user'");
        }

        $member = new self();

        $member->status = $data['status'];
        $member->user = User::fromArray($data['user']);

        $member->isAnonymous = $data['is_anonymous'] ?? false;
        $member->isMember = $data['is_member'] ?? false;
        $member->customTitle = $data['custom_title'] ?? null;
        $member->untilDate = $data['until_date'] ?? null;

        // Set permissions from data
        $member->setPermissionsFromArray($data);

        return $member;
    }

    /**
     * Set permissions from array data.
     *
     * @param array<string, mixed> $data
     */
    private function setPermissionsFromArray(array $data): void
    {
        $this->canBeEdited = $data['can_be_edited'] ?? false;
        $this->canChangeInfo = $data['can_change_info'] ?? false;
        $this->canInviteUsers = $data['can_invite_users'] ?? false;
        $this->canManageChat = $data['can_manage_chat'] ?? false;
        $this->canManageTopics = $data['can_manage_topics'] ?? false;
        $this->canManageVideoChats = $data['can_manage_video_chats'] ?? false;
        $this->canManageVoiceChats = $data['can_manage_voice_chats'] ?? false;
        $this->canManageDirectMessages = $data['can_manage_direct_messages'] ?? false;
        $this->canRestrictMembers = $data['can_restrict_members'] ?? false;
        $this->canPromoteMembers = $data['can_promote_members'] ?? false;
        $this->canPostMessages = $data['can_post_messages'] ?? false;
        $this->canEditMessages = $data['can_edit_messages'] ?? false;
        $this->canDeleteMessages = $data['can_delete_messages'] ?? false;
        $this->canPinMessages = $data['can_pin_messages'] ?? false;
        $this->canPostStories = $data['can_post_stories'] ?? false;
        $this->canEditStories = $data['can_edit_stories'] ?? false;
        $this->canDeleteStories = $data['can_delete_stories'] ?? false;
        $this->canSendMessages = $data['can_send_messages'] ?? false;
        $this->canSendMediaMessages = $data['can_send_media_messages'] ?? false;
        $this->canSendAudios = $data['can_send_audios'] ?? false;
        $this->canSendDocuments = $data['can_send_documents'] ?? false;
        $this->canSendPhotos = $data['can_send_photos'] ?? false;
        $this->canSendVideos = $data['can_send_videos'] ?? false;
        $this->canSendVideoNotes = $data['can_send_video_notes'] ?? false;
        $this->canSendVoiceNotes = $data['can_send_voice_notes'] ?? false;
        $this->canSendPolls = $data['can_send_polls'] ?? false;
        $this->canSendOtherMessages = $data['can_send_other_messages'] ?? false;
        $this->canAddWebPagePreviews = $data['can_add_web_page_previews'] ?? false;
    }

    /**
     * Get the member's status in the chat.
     */
    public function status(): string
    {
        return $this->status;
    }

    /**
     * Get the user information.
     */
    public function user(): User
    {
        return $this->user;
    }

    /**
     * Check if the member is anonymous.
     */
    public function isAnonymous(): bool
    {
        return $this->isAnonymous;
    }

    /**
     * Check if the user is a member of the chat.
     */
    public function isMember(): bool
    {
        return $this->isMember;
    }

    /**
     * Get the custom title for the member.
     */
    public function customTitle(): ?string
    {
        return $this->customTitle;
    }

    /**
     * Get the date until which the member is restricted/banned.
     */
    public function untilDate(): ?int
    {
        return $this->untilDate;
    }

    /**
     * Check if the member is the chat creator.
     */
    public function isCreator(): bool
    {
        return $this->status === self::STATUS_CREATOR;
    }

    /**
     * Check if the member is an administrator.
     */
    public function isAdministrator(): bool
    {
        return $this->status === self::STATUS_ADMINISTRATOR;
    }

    /**
     * Check if the member is restricted.
     */
    public function isRestricted(): bool
    {
        return $this->status === self::STATUS_RESTRICTED;
    }

    /**
     * Check if the member has left the chat.
     */
    public function hasLeft(): bool
    {
        return $this->status === self::STATUS_LEFT;
    }

    /**
     * Check if the member is kicked/banned.
     */
    public function isKicked(): bool
    {
        return $this->status === self::STATUS_KICKED;
    }

    // Administrative permission getters
    public function canBeEdited(): bool
    {
        return $this->canBeEdited;
    }

    public function canChangeInfo(): bool
    {
        return $this->canChangeInfo;
    }

    public function canInviteUsers(): bool
    {
        return $this->canInviteUsers;
    }

    public function canManageChat(): bool
    {
        return $this->canManageChat;
    }

    public function canManageTopics(): bool
    {
        return $this->canManageTopics;
    }

    public function canManageVideoChats(): bool
    {
        return $this->canManageVideoChats;
    }

    public function canManageVoiceChats(): bool
    {
        return $this->canManageVoiceChats;
    }

    public function canManageDirectMessages(): bool
    {
        return $this->canManageDirectMessages;
    }

    public function canRestrictMembers(): bool
    {
        return $this->canRestrictMembers;
    }

    public function canPromoteMembers(): bool
    {
        return $this->canPromoteMembers;
    }

    public function canPostMessages(): bool
    {
        return $this->canPostMessages;
    }

    public function canEditMessages(): bool
    {
        return $this->canEditMessages;
    }

    public function canDeleteMessages(): bool
    {
        return $this->canDeleteMessages;
    }

    public function canPinMessages(): bool
    {
        return $this->canPinMessages;
    }

    public function canPostStories(): bool
    {
        return $this->canPostStories;
    }

    public function canEditStories(): bool
    {
        return $this->canEditStories;
    }

    public function canDeleteStories(): bool
    {
        return $this->canDeleteStories;
    }

    // User permission getters
    public function canSendMessages(): bool
    {
        return $this->canSendMessages;
    }

    public function canSendMediaMessages(): bool
    {
        return $this->canSendMediaMessages;
    }

    public function canSendAudios(): bool
    {
        return $this->canSendAudios;
    }

    public function canSendDocuments(): bool
    {
        return $this->canSendDocuments;
    }

    public function canSendPhotos(): bool
    {
        return $this->canSendPhotos;
    }

    public function canSendVideos(): bool
    {
        return $this->canSendVideos;
    }

    public function canSendVideoNotes(): bool
    {
        return $this->canSendVideoNotes;
    }

    public function canSendVoiceNotes(): bool
    {
        return $this->canSendVoiceNotes;
    }

    public function canSendPolls(): bool
    {
        return $this->canSendPolls;
    }

    public function canSendOtherMessages(): bool
    {
        return $this->canSendOtherMessages;
    }

    public function canAddWebPagePreviews(): bool
    {
        return $this->canAddWebPagePreviews;
    }

    /**
     * Convert the chat member to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'status' => $this->status,
            'user' => $this->user->toArray(),
            'is_anonymous' => $this->isAnonymous,
            'is_member' => $this->isMember,
            'custom_title' => $this->customTitle,
            'until_date' => $this->untilDate,
            'can_be_edited' => $this->canBeEdited,
            'can_change_info' => $this->canChangeInfo,
            'can_invite_users' => $this->canInviteUsers,
            'can_manage_chat' => $this->canManageChat,
            'can_manage_topics' => $this->canManageTopics,
            'can_manage_video_chats' => $this->canManageVideoChats,
            'can_manage_voice_chats' => $this->canManageVoiceChats,
            'can_manage_direct_messages' => $this->canManageDirectMessages,
            'can_restrict_members' => $this->canRestrictMembers,
            'can_promote_members' => $this->canPromoteMembers,
            'can_post_messages' => $this->canPostMessages,
            'can_edit_messages' => $this->canEditMessages,
            'can_delete_messages' => $this->canDeleteMessages,
            'can_pin_messages' => $this->canPinMessages,
            'can_post_stories' => $this->canPostStories,
            'can_edit_stories' => $this->canEditStories,
            'can_delete_stories' => $this->canDeleteStories,
            'can_send_messages' => $this->canSendMessages,
            'can_send_media_messages' => $this->canSendMediaMessages,
            'can_send_audios' => $this->canSendAudios,
            'can_send_documents' => $this->canSendDocuments,
            'can_send_photos' => $this->canSendPhotos,
            'can_send_videos' => $this->canSendVideos,
            'can_send_video_notes' => $this->canSendVideoNotes,
            'can_send_voice_notes' => $this->canSendVoiceNotes,
            'can_send_polls' => $this->canSendPolls,
            'can_send_other_messages' => $this->canSendOtherMessages,
            'can_add_web_page_previews' => $this->canAddWebPagePreviews,
        ], fn ($value) => $value !== null);
    }
}
