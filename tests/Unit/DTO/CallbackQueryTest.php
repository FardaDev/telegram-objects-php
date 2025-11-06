<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/tests/Unit/DTO/CallbackQueryTest.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-07
 */

use Telegram\Objects\DTO\CallbackQuery;
use Telegram\Objects\DTO\Message;
use Telegram\Objects\DTO\User;
use Telegram\Objects\Exceptions\ValidationException;

it('can create callback query from array with minimal fields', function () {
    $callbackQuery = CallbackQuery::fromArray([
        'id' => 'callback_query_id',
        'from' => [
            'id' => 123456789,
            'first_name' => 'John',
            'is_bot' => false,
        ],
    ]);

    expect($callbackQuery->id())->toBe('callback_query_id');
    expect($callbackQuery->from())->toBeInstanceOf(User::class);
    expect($callbackQuery->from()->id())->toBe(123456789);
    expect($callbackQuery->message())->toBeNull();
    expect($callbackQuery->inlineMessageId())->toBeNull();
    expect($callbackQuery->chatInstance())->toBe('');
    expect($callbackQuery->data())->toBe('');
    expect($callbackQuery->gameShortName())->toBeNull();
    expect($callbackQuery->hasMessage())->toBeFalse();
    expect($callbackQuery->isInlineMessage())->toBeFalse();
    expect($callbackQuery->isGame())->toBeFalse();
});

it('can create callback query from array with all fields', function () {
    $callbackQuery = CallbackQuery::fromArray([
        'id' => 'callback_query_id',
        'from' => [
            'id' => 123456789,
            'first_name' => 'John',
            'is_bot' => false,
        ],
        'message' => [
            'message_id' => 456,
            'date' => 1699276800,
            'chat' => [
                'id' => '-123456789',
                'type' => 'group',
            ],
        ],
        'inline_message_id' => 'inline_msg_id',
        'chat_instance' => 'chat_instance_123',
        'data' => 'action:like;post_id:789',
        'game_short_name' => 'my_game',
    ]);

    expect($callbackQuery->id())->toBe('callback_query_id');
    expect($callbackQuery->from())->toBeInstanceOf(User::class);
    expect($callbackQuery->message())->toBeInstanceOf(Message::class);
    expect($callbackQuery->inlineMessageId())->toBe('inline_msg_id');
    expect($callbackQuery->chatInstance())->toBe('chat_instance_123');
    expect($callbackQuery->data())->toBe('action:like;post_id:789');
    expect($callbackQuery->gameShortName())->toBe('my_game');
    expect($callbackQuery->hasMessage())->toBeTrue();
    expect($callbackQuery->isInlineMessage())->toBeTrue();
    expect($callbackQuery->isGame())->toBeTrue();
});

it('can parse callback data', function () {
    $callbackQuery = CallbackQuery::fromArray([
        'id' => 'callback_query_id',
        'from' => [
            'id' => 123456789,
            'first_name' => 'John',
            'is_bot' => false,
        ],
        'data' => 'action:like;post_id:789;user_id:123',
    ]);

    $parsedData = $callbackQuery->parsedData();

    expect($parsedData->get('action'))->toBe('like');
    expect($parsedData->get('post_id'))->toBe('789');
    expect($parsedData->get('user_id'))->toBe('123');
});

it('can convert to array', function () {
    $data = [
        'id' => 'callback_query_id',
        'from' => [
            'id' => 123456789,
            'first_name' => 'John',
            'is_bot' => false,
        ],
        'chat_instance' => 'chat_instance_123',
        'data' => 'action:like',
    ];

    $callbackQuery = CallbackQuery::fromArray($data);
    $result = $callbackQuery->toArray();

    expect($result)->toHaveKey('id', 'callback_query_id');
    expect($result)->toHaveKey('from');
    expect($result)->toHaveKey('chat_instance', 'chat_instance_123');
    expect($result)->toHaveKey('data', 'action:like');
});

it('filters null values in toArray', function () {
    $callbackQuery = CallbackQuery::fromArray([
        'id' => 'callback_query_id',
        'from' => [
            'id' => 123456789,
            'first_name' => 'John',
            'is_bot' => false,
        ],
    ]);

    $result = $callbackQuery->toArray();

    expect($result)->not->toHaveKey('message');
    expect($result)->not->toHaveKey('inline_message_id');
    expect($result)->not->toHaveKey('chat_instance');
    expect($result)->not->toHaveKey('data');
    expect($result)->not->toHaveKey('game_short_name');
});

it('throws exception for missing id', function () {
    CallbackQuery::fromArray([
        'from' => [
            'id' => 123456789,
            'first_name' => 'John',
            'is_bot' => false,
        ],
    ]);
})->throws(ValidationException::class, "Missing required field 'id'");

it('throws exception for missing from', function () {
    CallbackQuery::fromArray([
        'id' => 'callback_query_id',
    ]);
})->throws(ValidationException::class, "Missing required field 'from'");
