<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: src/DTO/ChatMember.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-07
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Support\Validator;

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
     * @throws \Telegram\Objects\Exceptions\ValidationException If required fields are missing or invalid
     */
    public static function fromArray(array $data): self
    {
        Validator::requireField($data, 'status', 'ChatMember');
        Validator::requireField($data, 'user', 'ChatMember');

        $status = Validator::getValue($data, 'status', null, 'string');
        $userData = Validator::getValue($data, 'user', null, 'array');
        $isAnonymous = Validator::getValue($data, 'is_anonymous', false, 'bool');
        $isMember = Validator::getValue($data, 'is_member', false, 'bool');
        $customTitle = Validator::getValue($data, 'custom_title', null, 'string');
        $untilDate = Validator::getValue($data, 'until_date', null, 'int');

        $member = new self();

        $member->status = $status;
        $member->user = User::fromArray($userData);
        $member->isAnonymous = $isAnonymous;
        $member->isMember = $isMember;
        $member->customTitle = $customTitle;
        $member->untilDate = $untilDate;

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
        $this->canBeEdited = Validator::getValue($data, 'can_be_edited', false, 'bool');
        $this->canChangeInfo = Validator::getValue($data, 'can_change_info', false, 'bool');
        $this->canInviteUsers = Validator::getValue($data, 'can_invite_users', false, 'bool');
        $this->canManageChat = Validator::getValue($data, 'can_manage_chat', false, 'bool');
        $this->canManageTopics = Validator::getValue($data, 'can_manage_topics', false, 'bool');
        $this->canManageVideoChats = Validator::getValue($data, 'can_manage_video_chats', false, 'bool');
        $this->canManageVoiceChats = Validator::getValue($data, 'can_manage_voice_chats', false, 'bool');
        $this->canManageDirectMessages = Validator::getValue($data, 'can_manage_direct_messages', false, 'bool');
        $this->canRestrictMembers = Validator::getValue($data, 'can_restrict_members', false, 'bool');
        $this->canPromoteMembers = Validator::getValue($data, 'can_promote_members', false, 'bool');
        $this->canPostMessages = Validator::getValue($data, 'can_post_messages', false, 'bool');
        $this->canEditMessages = Validator::getValue($data, 'can_edit_messages', false, 'bool');
        $this->canDeleteMessages = Validator::getValue($data, 'can_delete_messages', false, 'bool');
        $this->canPinMessages = Validator::getValue($data, 'can_pin_messages', false, 'bool');
        $this->canPostStories = Validator::getValue($data, 'can_post_stories', false, 'bool');
        $this->canEditStories = Validator::getValue($data, 'can_edit_stories', false, 'bool');
        $this->canDeleteStories = Validator::getValue($data, 'can_delete_stories', false, 'bool');
        $this->canSendMessages = Validator::getValue($data, 'can_send_messages', false, 'bool');
        $this->canSendMediaMessages = Validator::getValue($data, 'can_send_media_messages', false, 'bool');
        $this->canSendAudios = Validator::getValue($data, 'can_send_audios', false, 'bool');
        $this->canSendDocuments = Validator::getValue($data, 'can_send_documents', false, 'bool');
        $this->canSendPhotos = Validator::getValue($data, 'can_send_photos', false, 'bool');
        $this->canSendVideos = Validator::getValue($data, 'can_send_videos', false, 'bool');
        $this->canSendVideoNotes = Validator::getValue($data, 'can_send_video_notes', false, 'bool');
        $this->canSendVoiceNotes = Validator::getValue($data, 'can_send_voice_notes', false, 'bool');
        $this->canSendPolls = Validator::getValue($data, 'can_send_polls', false, 'bool');
        $this->canSendOtherMessages = Validator::getValue($data, 'can_send_other_messages', false, 'bool');
        $this->canAddWebPagePreviews = Validator::getValue($data, 'can_add_web_page_previews', false, 'bool');
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
