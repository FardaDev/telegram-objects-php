<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/tests/Unit/DTO/MessageTest.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-07
 */

use Telegram\Objects\DTO\Message;
use Telegram\Objects\Exceptions\ValidationException;

it('can create message from array with all fields', function () {
    $data = [
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
        'message_thread_id' => 456,
        'edit_date' => 1699276900,
        'has_protected_content' => true,
        'forward_from' => [
            'id' => 111222333,
            'first_name' => 'Jane',
            'is_bot' => false,
        ],
    ];

    $message = Message::fromArray($data);

    expect($message->id())->toBe(123);
    expect($message->date()->getTimestamp())->toBe(1699276800);
    expect($message->chat()->id())->toBe('-123456789');
    expect($message->from()->id())->toBe(987654321);
    expect($message->text())->toBe('Hello, world!');
    expect($message->messageThreadId())->toBe(456);
    expect($message->editDate()->getTimestamp())->toBe(1699276900);
    expect($message->hasProtectedContent())->toBeTrue();
    expect($message->forwardedFrom()->id())->toBe(111222333);
});

it('can create message with minimal fields', function () {
    $data = [
        'message_id' => 123,
        'date' => 1699276800,
        'chat' => [
            'id' => '-123456789',
            'type' => 'group',
            'title' => 'Test Group',
        ],
    ];

    $message = Message::fromArray($data);

    expect($message->id())->toBe(123);
    expect($message->date()->getTimestamp())->toBe(1699276800);
    expect($message->chat()->id())->toBe('-123456789');
    expect($message->from())->toBeNull();
    expect($message->text())->toBe('');
    expect($message->messageThreadId())->toBeNull();
    expect($message->editDate())->toBeNull();
    expect($message->hasProtectedContent())->toBeFalse();
    expect($message->forwardedFrom())->toBeNull();
    expect($message->replyToMessage())->toBeNull();
});

it('can create message with reply', function () {
    $data = [
        'message_id' => 124,
        'date' => 1699276800,
        'chat' => [
            'id' => '-123456789',
            'type' => 'group',
            'title' => 'Test Group',
        ],
        'text' => 'This is a reply',
        'reply_to_message' => [
            'message_id' => 123,
            'date' => 1699276700,
            'chat' => [
                'id' => '-123456789',
                'type' => 'group',
                'title' => 'Test Group',
            ],
            'text' => 'Original message',
        ],
    ];

    $message = Message::fromArray($data);

    expect($message->id())->toBe(124);
    expect($message->text())->toBe('This is a reply');
    expect($message->isReply())->toBeTrue();
    expect($message->replyToMessage()->id())->toBe(123);
    expect($message->replyToMessage()->text())->toBe('Original message');
});

it('throws exception for missing message_id', function () {
    $data = [
        'date' => 1699276800,
        'chat' => [
            'id' => '-123456789',
            'type' => 'group',
        ],
    ];

    expect(fn () => Message::fromArray($data))
        ->toThrow(ValidationException::class, "Missing required field 'message_id'");
});

it('throws exception for missing date', function () {
    $data = [
        'message_id' => 123,
        'chat' => [
            'id' => '-123456789',
            'type' => 'group',
        ],
    ];

    expect(fn () => Message::fromArray($data))
        ->toThrow(ValidationException::class, "Missing required field 'date'");
});

it('throws exception for missing chat', function () {
    $data = [
        'message_id' => 123,
        'date' => 1699276800,
    ];

    expect(fn () => Message::fromArray($data))
        ->toThrow(ValidationException::class, "Missing required field 'chat'");
});

it('can check message properties', function () {
    $textMessage = Message::fromArray([
        'message_id' => 123,
        'date' => 1699276800,
        'chat' => ['id' => '1', 'type' => 'private'],
        'text' => 'Hello',
    ]);

    $emptyMessage = Message::fromArray([
        'message_id' => 124,
        'date' => 1699276800,
        'chat' => ['id' => '1', 'type' => 'private'],
    ]);

    $editedMessage = Message::fromArray([
        'message_id' => 125,
        'date' => 1699276800,
        'chat' => ['id' => '1', 'type' => 'private'],
        'edit_date' => 1699276900,
    ]);

    $forwardedMessage = Message::fromArray([
        'message_id' => 126,
        'date' => 1699276800,
        'chat' => ['id' => '1', 'type' => 'private'],
        'forward_from' => ['id' => 999, 'first_name' => 'Jane', 'is_bot' => false],
    ]);

    expect($textMessage->hasText())->toBeTrue();
    expect($emptyMessage->hasText())->toBeFalse();
    expect($editedMessage->isEdited())->toBeTrue();
    expect($textMessage->isEdited())->toBeFalse();
    expect($forwardedMessage->isForwarded())->toBeTrue();
    expect($textMessage->isForwarded())->toBeFalse();
});

it('can convert to array', function () {
    $data = [
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
        'edit_date' => 1699276900,
    ];

    $message = Message::fromArray($data);
    $result = $message->toArray();

    expect($result['message_id'])->toBe(123);
    expect($result['date'])->toBe(1699276800);
    expect($result['text'])->toBe('Hello, world!');
    expect($result['edit_date'])->toBe(1699276900);
    expect($result['chat'])->toBeArray();
    expect($result['from'])->toBeArray();
});

it('filters null values in toArray', function () {
    $data = [
        'message_id' => 123,
        'date' => 1699276800,
        'chat' => ['id' => '1', 'type' => 'private'],
    ];

    $message = Message::fromArray($data);
    $result = $message->toArray();

    expect($result)->toHaveKey('message_id');
    expect($result)->toHaveKey('date');
    expect($result)->toHaveKey('chat');
    expect($result)->not->toHaveKey('from');
    expect($result)->not->toHaveKey('text');
    expect($result)->not->toHaveKey('edit_date');
    expect($result)->not->toHaveKey('forward_from');
});
