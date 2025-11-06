<?php

declare(strict_types=1);

use Telegram\Objects\DTO\Chat;
use Telegram\Objects\Exceptions\ValidationException;

it('can create private chat from array', function () {
    $data = [
        'id' => '-123456789',
        'type' => 'private',
        'first_name' => 'John',
        'last_name' => 'Doe',
        'username' => 'johndoe',
    ];

    $chat = Chat::fromArray($data);

    expect($chat->id())->toBe('-123456789');
    expect($chat->type())->toBe('private');
    expect($chat->firstName())->toBe('John');
    expect($chat->lastName())->toBe('Doe');
    expect($chat->username())->toBe('johndoe');
    expect($chat->title())->toBeNull();
    expect($chat->isPrivate())->toBeTrue();
    expect($chat->isGroup())->toBeFalse();
    expect($chat->isChannel())->toBeFalse();
});

it('can create group chat from array', function () {
    $data = [
        'id' => '-987654321',
        'type' => 'group',
        'title' => 'Test Group',
    ];

    $chat = Chat::fromArray($data);

    expect($chat->id())->toBe('-987654321');
    expect($chat->type())->toBe('group');
    expect($chat->title())->toBe('Test Group');
    expect($chat->isPrivate())->toBeFalse();
    expect($chat->isGroup())->toBeTrue();
    expect($chat->isChannel())->toBeFalse();
});

it('can create supergroup chat from array', function () {
    $data = [
        'id' => '-100123456789',
        'type' => 'supergroup',
        'title' => 'Test Supergroup',
        'username' => 'testsupergroup',
        'is_forum' => true,
    ];

    $chat = Chat::fromArray($data);

    expect($chat->id())->toBe('-100123456789');
    expect($chat->type())->toBe('supergroup');
    expect($chat->title())->toBe('Test Supergroup');
    expect($chat->username())->toBe('testsupergroup');
    expect($chat->isForum())->toBeTrue();
    expect($chat->isGroup())->toBeTrue();
});

it('can create channel from array', function () {
    $data = [
        'id' => '-100987654321',
        'type' => 'channel',
        'title' => 'Test Channel',
        'username' => 'testchannel',
    ];

    $chat = Chat::fromArray($data);

    expect($chat->id())->toBe('-100987654321');
    expect($chat->type())->toBe('channel');
    expect($chat->title())->toBe('Test Channel');
    expect($chat->username())->toBe('testchannel');
    expect($chat->isChannel())->toBeTrue();
    expect($chat->isGroup())->toBeFalse();
    expect($chat->isPrivate())->toBeFalse();
});

it('throws exception for missing id', function () {
    $data = [
        'type' => 'private',
    ];

    expect(fn () => Chat::fromArray($data))
        ->toThrow(ValidationException::class, "Missing required field 'id'");
});

it('throws exception for missing type', function () {
    $data = [
        'id' => '-123456789',
    ];

    expect(fn () => Chat::fromArray($data))
        ->toThrow(ValidationException::class, "Missing required field 'type'");
});

it('throws exception for invalid chat type', function () {
    $data = [
        'id' => '-123456789',
        'type' => 'invalid_type',
    ];

    expect(fn () => Chat::fromArray($data))
        ->toThrow(ValidationException::class, "Invalid value 'invalid_type' for chat type");
});

it('can get display name for different chat types', function () {
    // Group with title
    $groupChat = Chat::fromArray([
        'id' => '-1',
        'type' => 'group',
        'title' => 'My Group',
    ]);
    expect($groupChat->displayName())->toBe('My Group');

    // Private chat with name
    $privateChat = Chat::fromArray([
        'id' => '1',
        'type' => 'private',
        'first_name' => 'John',
        'last_name' => 'Doe',
    ]);
    expect($privateChat->displayName())->toBe('John Doe');

    // Private chat with username only
    $usernameChat = Chat::fromArray([
        'id' => '2',
        'type' => 'private',
        'username' => 'johndoe',
    ]);
    expect($usernameChat->displayName())->toBe('@johndoe');

    // Chat with only ID
    $idOnlyChat = Chat::fromArray([
        'id' => '3',
        'type' => 'private',
    ]);
    expect($idOnlyChat->displayName())->toBe('Chat 3');
});

it('can convert to array', function () {
    $data = [
        'id' => '-123456789',
        'type' => 'supergroup',
        'title' => 'Test Group',
        'username' => 'testgroup',
        'is_forum' => true,
        'is_direct_messages' => false,
    ];

    $chat = Chat::fromArray($data);
    $result = $chat->toArray();

    expect($result)->toBe($data);
});

it('filters null values in toArray', function () {
    $data = [
        'id' => '-123456789',
        'type' => 'private',
        'first_name' => 'John',
    ];

    $chat = Chat::fromArray($data);
    $result = $chat->toArray();

    expect($result)->toBe([
        'id' => '-123456789',
        'type' => 'private',
        'first_name' => 'John',
        'is_forum' => false,
        'is_direct_messages' => false,
    ]);
    expect($result)->not->toHaveKey('title');
    expect($result)->not->toHaveKey('username');
    expect($result)->not->toHaveKey('last_name');
});
