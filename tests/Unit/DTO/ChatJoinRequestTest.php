<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/tests/Unit/DTO/ChatJoinRequestTest.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-07
 */

use Telegram\Objects\DTO\Chat;
use Telegram\Objects\DTO\ChatInviteLink;
use Telegram\Objects\DTO\ChatJoinRequest;
use Telegram\Objects\DTO\User;
use Telegram\Objects\Exceptions\ValidationException;
use Telegram\Objects\Support\TelegramDateTime;

it('can create chat join request from array with minimal fields', function () {
    $data = [
        'user_chat_id' => 987654321,
        'date' => 1640995200,
        'chat' => [
            'id' => '-1001234567890',
            'type' => 'supergroup',
            'title' => 'Test Group',
        ],
        'from' => [
            'id' => 987654321,
            'is_bot' => false,
            'first_name' => 'John',
        ],
    ];

    $request = ChatJoinRequest::fromArray($data);

    expect($request->userChatId())->toBe(987654321);
    expect($request->date())->toBeInstanceOf(TelegramDateTime::class);
    expect($request->date()->getTimestamp())->toBe(1640995200);
    expect($request->chat())->toBeInstanceOf(Chat::class);
    expect($request->chat()->id())->toBe('-1001234567890');
    expect($request->from())->toBeInstanceOf(User::class);
    expect($request->from()->id())->toBe(987654321);
    expect($request->bio())->toBeNull();
    expect($request->inviteLink())->toBeNull();
});

it('can create chat join request from array with all fields', function () {
    $data = [
        'user_chat_id' => 987654321,
        'date' => 1640995200,
        'chat' => [
            'id' => '-1001234567890',
            'type' => 'supergroup',
            'title' => 'Test Group',
        ],
        'from' => [
            'id' => 987654321,
            'is_bot' => false,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'johndoe',
        ],
        'bio' => 'I would like to join this group to learn more about the topic.',
        'invite_link' => [
            'invite_link' => 'https://t.me/+abc123',
            'creator' => [
                'id' => 123456789,
                'is_bot' => false,
                'first_name' => 'Admin',
            ],
            'creates_join_request' => true,
            'is_primary' => false,
            'is_revoked' => false,
        ],
    ];

    $request = ChatJoinRequest::fromArray($data);

    expect($request->userChatId())->toBe(987654321);
    expect($request->from()->username())->toBe('johndoe');
    expect($request->bio())->toBe('I would like to join this group to learn more about the topic.');
    expect($request->inviteLink())->toBeInstanceOf(ChatInviteLink::class);
    expect($request->inviteLink()->inviteLink())->toBe('https://t.me/+abc123');
});

it('throws exception for missing user_chat_id', function () {
    $data = [
        'date' => 1640995200,
        'chat' => [
            'id' => '-1001234567890',
            'type' => 'supergroup',
            'title' => 'Test Group',
        ],
        'from' => [
            'id' => 987654321,
            'is_bot' => false,
            'first_name' => 'John',
        ],
    ];

    expect(fn () => ChatJoinRequest::fromArray($data))
        ->toThrow(ValidationException::class);
});

it('throws exception for missing date', function () {
    $data = [
        'user_chat_id' => 987654321,
        'chat' => [
            'id' => '-1001234567890',
            'type' => 'supergroup',
            'title' => 'Test Group',
        ],
        'from' => [
            'id' => 987654321,
            'is_bot' => false,
            'first_name' => 'John',
        ],
    ];

    expect(fn () => ChatJoinRequest::fromArray($data))
        ->toThrow(ValidationException::class);
});

it('throws exception for missing chat', function () {
    $data = [
        'user_chat_id' => 987654321,
        'date' => 1640995200,
        'from' => [
            'id' => 987654321,
            'is_bot' => false,
            'first_name' => 'John',
        ],
    ];

    expect(fn () => ChatJoinRequest::fromArray($data))
        ->toThrow(ValidationException::class);
});

it('throws exception for missing from', function () {
    $data = [
        'user_chat_id' => 987654321,
        'date' => 1640995200,
        'chat' => [
            'id' => '-1001234567890',
            'type' => 'supergroup',
            'title' => 'Test Group',
        ],
    ];

    expect(fn () => ChatJoinRequest::fromArray($data))
        ->toThrow(ValidationException::class);
});

it('can check if request has bio', function () {
    $withBioData = [
        'user_chat_id' => 987654321,
        'date' => 1640995200,
        'chat' => [
            'id' => '-1001234567890',
            'type' => 'supergroup',
            'title' => 'Test Group',
        ],
        'from' => [
            'id' => 987654321,
            'is_bot' => false,
            'first_name' => 'John',
        ],
        'bio' => 'I want to join this group.',
    ];

    $withoutBioData = [
        'user_chat_id' => 987654321,
        'date' => 1640995200,
        'chat' => [
            'id' => '-1001234567890',
            'type' => 'supergroup',
            'title' => 'Test Group',
        ],
        'from' => [
            'id' => 987654321,
            'is_bot' => false,
            'first_name' => 'John',
        ],
    ];

    $requestWithBio = ChatJoinRequest::fromArray($withBioData);
    $requestWithoutBio = ChatJoinRequest::fromArray($withoutBioData);

    expect($requestWithBio->hasBio())->toBeTrue();
    expect($requestWithoutBio->hasBio())->toBeFalse();
});

it('can check if request has invite link', function () {
    $withLinkData = [
        'user_chat_id' => 987654321,
        'date' => 1640995200,
        'chat' => [
            'id' => '-1001234567890',
            'type' => 'supergroup',
            'title' => 'Test Group',
        ],
        'from' => [
            'id' => 987654321,
            'is_bot' => false,
            'first_name' => 'John',
        ],
        'invite_link' => [
            'invite_link' => 'https://t.me/+abc123',
            'creator' => [
                'id' => 123456789,
                'is_bot' => false,
                'first_name' => 'Admin',
            ],
            'creates_join_request' => true,
            'is_primary' => false,
            'is_revoked' => false,
        ],
    ];

    $withoutLinkData = [
        'user_chat_id' => 987654321,
        'date' => 1640995200,
        'chat' => [
            'id' => '-1001234567890',
            'type' => 'supergroup',
            'title' => 'Test Group',
        ],
        'from' => [
            'id' => 987654321,
            'is_bot' => false,
            'first_name' => 'John',
        ],
    ];

    $requestWithLink = ChatJoinRequest::fromArray($withLinkData);
    $requestWithoutLink = ChatJoinRequest::fromArray($withoutLinkData);

    expect($requestWithLink->hasInviteLink())->toBeTrue();
    expect($requestWithoutLink->hasInviteLink())->toBeFalse();
});

it('can get age of request', function () {
    $recentTime = time() - 300; // 5 minutes ago
    $data = [
        'user_chat_id' => 987654321,
        'date' => $recentTime,
        'chat' => [
            'id' => '-1001234567890',
            'type' => 'supergroup',
            'title' => 'Test Group',
        ],
        'from' => [
            'id' => 987654321,
            'is_bot' => false,
            'first_name' => 'John',
        ],
    ];

    $request = ChatJoinRequest::fromArray($data);
    $age = $request->ageInSeconds();

    expect($age)->toBeGreaterThan(250); // Should be around 300 seconds
    expect($age)->toBeLessThan(350);
});

it('can check if request is recent', function () {
    $recentTime = time() - 300; // 5 minutes ago
    $oldTime = time() - 7200; // 2 hours ago

    $recentData = [
        'user_chat_id' => 987654321,
        'date' => $recentTime,
        'chat' => [
            'id' => '-1001234567890',
            'type' => 'supergroup',
            'title' => 'Test Group',
        ],
        'from' => [
            'id' => 987654321,
            'is_bot' => false,
            'first_name' => 'John',
        ],
    ];

    $oldData = [
        'user_chat_id' => 987654321,
        'date' => $oldTime,
        'chat' => [
            'id' => '-1001234567890',
            'type' => 'supergroup',
            'title' => 'Test Group',
        ],
        'from' => [
            'id' => 987654321,
            'is_bot' => false,
            'first_name' => 'John',
        ],
    ];

    $recentRequest = ChatJoinRequest::fromArray($recentData);
    $oldRequest = ChatJoinRequest::fromArray($oldData);

    expect($recentRequest->isRecent())->toBeTrue();
    expect($oldRequest->isRecent())->toBeFalse();
});

it('can get age string', function () {
    $data = [
        'user_chat_id' => 987654321,
        'date' => time() - 120, // 2 minutes ago
        'chat' => [
            'id' => '-1001234567890',
            'type' => 'supergroup',
            'title' => 'Test Group',
        ],
        'from' => [
            'id' => 987654321,
            'is_bot' => false,
            'first_name' => 'John',
        ],
    ];

    $request = ChatJoinRequest::fromArray($data);
    $ageString = $request->getAgeString();

    expect($ageString)->toContain('minute');
    expect($ageString)->toContain('ago');
});

it('can convert to array', function () {
    $data = [
        'user_chat_id' => 987654321,
        'date' => 1640995200,
        'chat' => [
            'id' => '-1001234567890',
            'type' => 'supergroup',
            'title' => 'Test Group',
        ],
        'from' => [
            'id' => 987654321,
            'is_bot' => false,
            'first_name' => 'John',
        ],
        'bio' => 'Test bio',
    ];

    $request = ChatJoinRequest::fromArray($data);
    $array = $request->toArray();

    expect($array)->toHaveKey('user_chat_id');
    expect($array)->toHaveKey('date');
    expect($array)->toHaveKey('chat');
    expect($array)->toHaveKey('from');
    expect($array)->toHaveKey('bio');
    expect($array['user_chat_id'])->toBe(987654321);
    expect($array['date'])->toBe(1640995200);
    expect($array['bio'])->toBe('Test bio');
});

it('filters null values in toArray', function () {
    $data = [
        'user_chat_id' => 987654321,
        'date' => 1640995200,
        'chat' => [
            'id' => '-1001234567890',
            'type' => 'supergroup',
            'title' => 'Test Group',
        ],
        'from' => [
            'id' => 987654321,
            'is_bot' => false,
            'first_name' => 'John',
        ],
    ];

    $request = ChatJoinRequest::fromArray($data);
    $array = $request->toArray();

    expect($array)->not->toHaveKey('bio');
    expect($array)->not->toHaveKey('invite_link');
});
