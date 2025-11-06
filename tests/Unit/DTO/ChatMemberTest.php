<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/tests/Unit/DTO/ChatMemberTest.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-07
 */

use Telegram\Objects\DTO\ChatMember;
use Telegram\Objects\DTO\User;
use Telegram\Objects\Exceptions\ValidationException;

it('can create chat member from array with minimal fields', function () {
    $data = [
        'status' => ChatMember::STATUS_MEMBER,
        'user' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'John',
        ],
    ];

    $member = ChatMember::fromArray($data);

    expect($member->status())->toBe(ChatMember::STATUS_MEMBER);
    expect($member->user())->toBeInstanceOf(User::class);
    expect($member->user()->id())->toBe(123456789);
    expect($member->isAnonymous())->toBeFalse();
    expect($member->isMember())->toBeFalse();
    expect($member->customTitle())->toBeNull();
    expect($member->untilDate())->toBeNull();
});

it('can create chat member from array with all fields', function () {
    $data = [
        'status' => ChatMember::STATUS_ADMINISTRATOR,
        'user' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'johndoe',
        ],
        'is_anonymous' => true,
        'is_member' => true,
        'custom_title' => 'Super Admin',
        'until_date' => 1640995200,
        'can_be_edited' => true,
        'can_change_info' => true,
        'can_invite_users' => true,
        'can_manage_chat' => true,
        'can_manage_topics' => true,
        'can_manage_video_chats' => true,
        'can_manage_voice_chats' => true,
        'can_manage_direct_messages' => true,
        'can_restrict_members' => true,
        'can_promote_members' => true,
        'can_post_messages' => true,
        'can_edit_messages' => true,
        'can_delete_messages' => true,
        'can_pin_messages' => true,
        'can_post_stories' => true,
        'can_edit_stories' => true,
        'can_delete_stories' => true,
        'can_send_messages' => true,
        'can_send_media_messages' => true,
        'can_send_audios' => true,
        'can_send_documents' => true,
        'can_send_photos' => true,
        'can_send_videos' => true,
        'can_send_video_notes' => true,
        'can_send_voice_notes' => true,
        'can_send_polls' => true,
        'can_send_other_messages' => true,
        'can_add_web_page_previews' => true,
    ];

    $member = ChatMember::fromArray($data);

    expect($member->status())->toBe(ChatMember::STATUS_ADMINISTRATOR);
    expect($member->user()->firstName())->toBe('John');
    expect($member->isAnonymous())->toBeTrue();
    expect($member->isMember())->toBeTrue();
    expect($member->customTitle())->toBe('Super Admin');
    expect($member->untilDate())->toBe(1640995200);
    expect($member->canBeEdited())->toBeTrue();
    expect($member->canChangeInfo())->toBeTrue();
    expect($member->canInviteUsers())->toBeTrue();
    expect($member->canManageChat())->toBeTrue();
    expect($member->canManageTopics())->toBeTrue();
    expect($member->canManageVideoChats())->toBeTrue();
    expect($member->canManageVoiceChats())->toBeTrue();
    expect($member->canManageDirectMessages())->toBeTrue();
    expect($member->canRestrictMembers())->toBeTrue();
    expect($member->canPromoteMembers())->toBeTrue();
    expect($member->canPostMessages())->toBeTrue();
    expect($member->canEditMessages())->toBeTrue();
    expect($member->canDeleteMessages())->toBeTrue();
    expect($member->canPinMessages())->toBeTrue();
    expect($member->canPostStories())->toBeTrue();
    expect($member->canEditStories())->toBeTrue();
    expect($member->canDeleteStories())->toBeTrue();
    expect($member->canSendMessages())->toBeTrue();
    expect($member->canSendMediaMessages())->toBeTrue();
    expect($member->canSendAudios())->toBeTrue();
    expect($member->canSendDocuments())->toBeTrue();
    expect($member->canSendPhotos())->toBeTrue();
    expect($member->canSendVideos())->toBeTrue();
    expect($member->canSendVideoNotes())->toBeTrue();
    expect($member->canSendVoiceNotes())->toBeTrue();
    expect($member->canSendPolls())->toBeTrue();
    expect($member->canSendOtherMessages())->toBeTrue();
    expect($member->canAddWebPagePreviews())->toBeTrue();
});

it('throws exception for missing status', function () {
    $data = [
        'user' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'John',
        ],
    ];

    expect(fn () => ChatMember::fromArray($data))
        ->toThrow(ValidationException::class);
});

it('throws exception for missing user', function () {
    $data = [
        'status' => ChatMember::STATUS_MEMBER,
    ];

    expect(fn () => ChatMember::fromArray($data))
        ->toThrow(ValidationException::class);
});

it('can check if member is creator', function () {
    $data = [
        'status' => ChatMember::STATUS_CREATOR,
        'user' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'John',
        ],
    ];

    $member = ChatMember::fromArray($data);

    expect($member->isCreator())->toBeTrue();
    expect($member->isAdministrator())->toBeFalse();
    expect($member->isRestricted())->toBeFalse();
    expect($member->hasLeft())->toBeFalse();
    expect($member->isKicked())->toBeFalse();
});

it('can check if member is administrator', function () {
    $data = [
        'status' => ChatMember::STATUS_ADMINISTRATOR,
        'user' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'John',
        ],
    ];

    $member = ChatMember::fromArray($data);

    expect($member->isCreator())->toBeFalse();
    expect($member->isAdministrator())->toBeTrue();
    expect($member->isRestricted())->toBeFalse();
    expect($member->hasLeft())->toBeFalse();
    expect($member->isKicked())->toBeFalse();
});

it('can check member permissions', function () {
    $data = [
        'status' => ChatMember::STATUS_ADMINISTRATOR,
        'user' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'John',
        ],
        'can_manage_chat' => true,
        'can_restrict_members' => true,
        'can_promote_members' => true,
        'can_post_messages' => true,
        'can_edit_messages' => true,
        'can_delete_messages' => true,
    ];

    $member = ChatMember::fromArray($data);

    expect($member->canManageChat())->toBeTrue();
    expect($member->canRestrictMembers())->toBeTrue();
    expect($member->canPromoteMembers())->toBeTrue();
    expect($member->canPostMessages())->toBeTrue();
    expect($member->canEditMessages())->toBeTrue();
    expect($member->canDeleteMessages())->toBeTrue();
});

it('can convert to array', function () {
    $data = [
        'status' => ChatMember::STATUS_MEMBER,
        'user' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'John',
        ],
        'is_anonymous' => false,
        'is_member' => true,
        'custom_title' => 'Member',
    ];

    $member = ChatMember::fromArray($data);
    $array = $member->toArray();

    expect($array)->toHaveKey('status');
    expect($array)->toHaveKey('user');
    expect($array['status'])->toBe(ChatMember::STATUS_MEMBER);
    expect($array['user'])->toBeArray();
    expect($array['user']['id'])->toBe(123456789);
});

it('filters null values in toArray', function () {
    $data = [
        'status' => ChatMember::STATUS_MEMBER,
        'user' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'John',
        ],
    ];

    $member = ChatMember::fromArray($data);
    $array = $member->toArray();

    expect($array)->not->toHaveKey('custom_title');
    expect($array)->not->toHaveKey('until_date');
});
