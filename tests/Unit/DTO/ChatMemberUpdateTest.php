<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/tests/Unit/DTO/ChatMemberUpdateTest.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-07
 */

use Telegram\Objects\DTO\Chat;
use Telegram\Objects\DTO\ChatMember;
use Telegram\Objects\DTO\ChatMemberUpdate;
use Telegram\Objects\DTO\User;
use Telegram\Objects\Exceptions\ValidationException;
use Telegram\Objects\Support\TelegramDateTime;

it('can create chat member update from array with minimal fields', function () {
    $data = [
        'date' => 1640995200,
        'chat' => [
            'id' => '-1001234567890',
            'type' => 'supergroup',
            'title' => 'Test Group',
        ],
        'from' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'Admin',
        ],
        'old_chat_member' => [
            'status' => ChatMember::STATUS_MEMBER,
            'user' => [
                'id' => 987654321,
                'is_bot' => false,
                'first_name' => 'John',
            ],
        ],
        'new_chat_member' => [
            'status' => ChatMember::STATUS_ADMINISTRATOR,
            'user' => [
                'id' => 987654321,
                'is_bot' => false,
                'first_name' => 'John',
            ],
            'can_manage_chat' => true,
        ],
    ];

    $update = ChatMemberUpdate::fromArray($data);

    expect($update->date())->toBeInstanceOf(TelegramDateTime::class);
    expect($update->date()->getTimestamp())->toBe(1640995200);
    expect($update->chat())->toBeInstanceOf(Chat::class);
    expect($update->chat()->id())->toBe('-1001234567890');
    expect($update->from())->toBeInstanceOf(User::class);
    expect($update->from()->id())->toBe(123456789);
    expect($update->previous())->toBeInstanceOf(ChatMember::class);
    expect($update->previous()->status())->toBe(ChatMember::STATUS_MEMBER);
    expect($update->new())->toBeInstanceOf(ChatMember::class);
    expect($update->new()->status())->toBe(ChatMember::STATUS_ADMINISTRATOR);
    expect($update->inviteLink())->toBeNull();
    expect($update->viaJoinRequest())->toBeNull();
    expect($update->viaChatFolderInviteLink())->toBeNull();
});

it('throws exception for missing date', function () {
    $data = [
        'chat' => [
            'id' => '-1001234567890',
            'type' => 'supergroup',
            'title' => 'Test Group',
        ],
        'from' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'Admin',
        ],
        'old_chat_member' => [
            'status' => ChatMember::STATUS_MEMBER,
            'user' => [
                'id' => 987654321,
                'is_bot' => false,
                'first_name' => 'John',
            ],
        ],
        'new_chat_member' => [
            'status' => ChatMember::STATUS_ADMINISTRATOR,
            'user' => [
                'id' => 987654321,
                'is_bot' => false,
                'first_name' => 'John',
            ],
        ],
    ];

    expect(fn () => ChatMemberUpdate::fromArray($data))
        ->toThrow(ValidationException::class);
});

it('can check if update is promotion', function () {
    $data = [
        'date' => 1640995200,
        'chat' => [
            'id' => '-1001234567890',
            'type' => 'supergroup',
            'title' => 'Test Group',
        ],
        'from' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'Admin',
        ],
        'old_chat_member' => [
            'status' => ChatMember::STATUS_MEMBER,
            'user' => [
                'id' => 987654321,
                'is_bot' => false,
                'first_name' => 'John',
            ],
        ],
        'new_chat_member' => [
            'status' => ChatMember::STATUS_ADMINISTRATOR,
            'user' => [
                'id' => 987654321,
                'is_bot' => false,
                'first_name' => 'John',
            ],
        ],
    ];

    $update = ChatMemberUpdate::fromArray($data);

    expect($update->isPromotion())->toBeTrue();
    expect($update->isDemotion())->toBeFalse();
});

it('can convert to array', function () {
    $data = [
        'date' => 1640995200,
        'chat' => [
            'id' => '-1001234567890',
            'type' => 'supergroup',
            'title' => 'Test Group',
        ],
        'from' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'Admin',
        ],
        'old_chat_member' => [
            'status' => ChatMember::STATUS_MEMBER,
            'user' => [
                'id' => 987654321,
                'is_bot' => false,
                'first_name' => 'John',
            ],
        ],
        'new_chat_member' => [
            'status' => ChatMember::STATUS_ADMINISTRATOR,
            'user' => [
                'id' => 987654321,
                'is_bot' => false,
                'first_name' => 'John',
            ],
        ],
    ];

    $update = ChatMemberUpdate::fromArray($data);
    $array = $update->toArray();

    expect($array)->toHaveKey('date');
    expect($array)->toHaveKey('chat');
    expect($array)->toHaveKey('from');
    expect($array)->toHaveKey('old_chat_member');
    expect($array)->toHaveKey('new_chat_member');
    expect($array['date'])->toBe(1640995200);
});

it('filters null values in toArray', function () {
    $data = [
        'date' => 1640995200,
        'chat' => [
            'id' => '-1001234567890',
            'type' => 'supergroup',
            'title' => 'Test Group',
        ],
        'from' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'Admin',
        ],
        'old_chat_member' => [
            'status' => ChatMember::STATUS_MEMBER,
            'user' => [
                'id' => 987654321,
                'is_bot' => false,
                'first_name' => 'John',
            ],
        ],
        'new_chat_member' => [
            'status' => ChatMember::STATUS_ADMINISTRATOR,
            'user' => [
                'id' => 987654321,
                'is_bot' => false,
                'first_name' => 'John',
            ],
        ],
    ];

    $update = ChatMemberUpdate::fromArray($data);
    $array = $update->toArray();

    expect($array)->not->toHaveKey('invite_link');
    expect($array)->not->toHaveKey('via_join_request');
    expect($array)->not->toHaveKey('via_chat_folder_invite_link');
});
