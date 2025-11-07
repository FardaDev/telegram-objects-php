<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: tests/Unit/DTO/TelegramUpdateTest.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-07
 */

use Telegram\Objects\DTO\Message;
use Telegram\Objects\DTO\TelegramUpdate;
use Telegram\Objects\Exceptions\ValidationException;

it('can create update from array with message', function () {
    $data = [
        'update_id' => 123456789,
        'message' => [
            'message_id' => 123,
            'date' => 1699276800,
            'chat' => [
                'id' => '-123456789',
                'type' => 'group',
                'title' => 'Test Group',
            ],
            'from' => [
                'id' => 987654321,
                'first_name' => 'John',
                'is_bot' => false,
            ],
            'text' => 'Hello, world!',
        ],
    ];

    $update = TelegramUpdate::fromArray($data);

    expect($update->id())->toBe(123456789);
    expect($update->hasMessage())->toBeTrue();
    expect($update->message()->id())->toBe(123);
    expect($update->message()->text())->toBe('Hello, world!');
    expect($update->getType())->toBe('message');
});

it('can create update from array with edited message', function () {
    $data = [
        'update_id' => 123456790,
        'edited_message' => [
            'message_id' => 124,
            'date' => 1699276800,
            'chat' => [
                'id' => '-123456789',
                'type' => 'group',
                'title' => 'Test Group',
            ],
            'text' => 'Edited message',
            'edit_date' => 1699276900,
        ],
    ];

    $update = TelegramUpdate::fromArray($data);

    expect($update->id())->toBe(123456790);
    expect($update->hasMessage())->toBeTrue();
    expect($update->message()->id())->toBe(124);
    expect($update->message()->text())->toBe('Edited message');
    expect($update->message()->isEdited())->toBeTrue();
});

it('can create update from array with channel post', function () {
    $data = [
        'update_id' => 123456791,
        'channel_post' => [
            'message_id' => 125,
            'date' => 1699276800,
            'chat' => [
                'id' => '-100123456789',
                'type' => 'channel',
                'title' => 'Test Channel',
            ],
            'text' => 'Channel post',
        ],
    ];

    $update = TelegramUpdate::fromArray($data);

    expect($update->id())->toBe(123456791);
    expect($update->hasMessage())->toBeTrue();
    expect($update->message()->id())->toBe(125);
    expect($update->message()->text())->toBe('Channel post');
    expect($update->message()->chat()->isChannel())->toBeTrue();
});

it('can create update from array with edited channel post', function () {
    $data = [
        'update_id' => 123456792,
        'edited_channel_post' => [
            'message_id' => 126,
            'date' => 1699276800,
            'chat' => [
                'id' => '-100123456789',
                'type' => 'channel',
                'title' => 'Test Channel',
            ],
            'text' => 'Edited channel post',
            'edit_date' => 1699276900,
        ],
    ];

    $update = TelegramUpdate::fromArray($data);

    expect($update->id())->toBe(123456792);
    expect($update->hasMessage())->toBeTrue();
    expect($update->message()->id())->toBe(126);
    expect($update->message()->text())->toBe('Edited channel post');
    expect($update->message()->isEdited())->toBeTrue();
});

it('can create update without message', function () {
    $data = [
        'update_id' => 123456793,
        // No message or other update types
    ];

    $update = TelegramUpdate::fromArray($data);

    expect($update->id())->toBe(123456793);
    expect($update->hasMessage())->toBeFalse();
    expect($update->message())->toBeNull();
    expect($update->getType())->toBe('unknown');
});

it('throws exception for missing update_id', function () {
    $data = [
        'message' => [
            'message_id' => 123,
            'date' => 1699276800,
            'chat' => ['id' => '1', 'type' => 'private'],
        ],
    ];

    expect(fn () => TelegramUpdate::fromArray($data))
        ->toThrow(ValidationException::class, "Missing required field 'update_id'");
});

it('can convert to array', function () {
    $data = [
        'update_id' => 123456789,
        'message' => [
            'message_id' => 123,
            'date' => 1699276800,
            'chat' => [
                'id' => '-123456789',
                'type' => 'group',
                'title' => 'Test Group',
            ],
            'text' => 'Hello, world!',
        ],
    ];

    $update = TelegramUpdate::fromArray($data);
    $result = $update->toArray();

    expect($result['update_id'])->toBe(123456789);
    expect($result['message'])->toBeArray();
    expect($result['message']['message_id'])->toBe(123);
    expect($result['message']['text'])->toBe('Hello, world!');
});

it('filters null values in toArray', function () {
    $data = [
        'update_id' => 123456789,
    ];

    $update = TelegramUpdate::fromArray($data);
    $result = $update->toArray();

    expect($result)->toBe([
        'update_id' => 123456789,
    ]);
    expect($result)->not->toHaveKey('message');
});
