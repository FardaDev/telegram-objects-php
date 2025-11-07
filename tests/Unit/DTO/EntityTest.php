<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: tests/Unit/DTO/EntityTest.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-07
 */

use Telegram\Objects\DTO\Entity;
use Telegram\Objects\DTO\User;
use Telegram\Objects\Exceptions\ValidationException;

it('can create entity from array with minimal fields', function () {
    $entity = Entity::fromArray([
        'type' => 'hashtag',
        'offset' => 0,
        'length' => 5,
    ]);

    expect($entity->type())->toBe('hashtag');
    expect($entity->offset())->toBe(0);
    expect($entity->length())->toBe(5);
    expect($entity->url())->toBeNull();
    expect($entity->user())->toBeNull();
    expect($entity->language())->toBeNull();
    expect($entity->customEmojiId())->toBeNull();
    expect($entity->isHashtag())->toBeTrue();
    expect($entity->isTextLink())->toBeFalse();
    expect($entity->isTextMention())->toBeFalse();
});

it('can create entity from array with all fields', function () {
    $entity = Entity::fromArray([
        'type' => 'text_mention',
        'offset' => 10,
        'length' => 8,
        'url' => 'https://example.com',
        'user' => [
            'id' => 123456789,
            'first_name' => 'John',
            'is_bot' => false,
        ],
        'language' => 'php',
        'custom_emoji_id' => 'emoji_123',
    ]);

    expect($entity->type())->toBe('text_mention');
    expect($entity->offset())->toBe(10);
    expect($entity->length())->toBe(8);
    expect($entity->url())->toBe('https://example.com');
    expect($entity->user())->toBeInstanceOf(User::class);
    expect($entity->language())->toBe('php');
    expect($entity->customEmojiId())->toBe('emoji_123');
    expect($entity->isTextMention())->toBeTrue();
    expect($entity->isHashtag())->toBeFalse();
});

it('can identify different entity types', function () {
    $urlEntity = Entity::fromArray([
        'type' => 'url',
        'offset' => 0,
        'length' => 20,
    ]);

    $mentionEntity = Entity::fromArray([
        'type' => 'mention',
        'offset' => 0,
        'length' => 10,
    ]);

    $preEntity = Entity::fromArray([
        'type' => 'pre',
        'offset' => 0,
        'length' => 15,
    ]);

    $customEmojiEntity = Entity::fromArray([
        'type' => 'custom_emoji',
        'offset' => 0,
        'length' => 2,
    ]);

    expect($urlEntity->isUrl())->toBeTrue();
    expect($mentionEntity->isMention())->toBeTrue();
    expect($preEntity->isPreformatted())->toBeTrue();
    expect($customEmojiEntity->isCustomEmoji())->toBeTrue();
});

it('can convert to array', function () {
    $data = [
        'type' => 'text_link',
        'offset' => 5,
        'length' => 10,
        'url' => 'https://example.com',
        'language' => 'javascript',
    ];

    $entity = Entity::fromArray($data);
    $result = $entity->toArray();

    expect($result)->toHaveKey('type', 'text_link');
    expect($result)->toHaveKey('offset', 5);
    expect($result)->toHaveKey('length', 10);
    expect($result)->toHaveKey('url', 'https://example.com');
    expect($result)->toHaveKey('language', 'javascript');
});

it('filters null values in toArray', function () {
    $entity = Entity::fromArray([
        'type' => 'hashtag',
        'offset' => 0,
        'length' => 5,
    ]);

    $result = $entity->toArray();

    expect($result)->not->toHaveKey('url');
    expect($result)->not->toHaveKey('user');
    expect($result)->not->toHaveKey('language');
    expect($result)->not->toHaveKey('custom_emoji_id');
});

it('throws exception for missing type', function () {
    Entity::fromArray([
        'offset' => 0,
        'length' => 5,
    ]);
})->throws(ValidationException::class, "Missing required field 'type'");

it('throws exception for missing offset', function () {
    Entity::fromArray([
        'type' => 'hashtag',
        'length' => 5,
    ]);
})->throws(ValidationException::class, "Missing required field 'offset'");

it('throws exception for missing length', function () {
    Entity::fromArray([
        'type' => 'hashtag',
        'offset' => 0,
    ]);
})->throws(ValidationException::class, "Missing required field 'length'");

it('throws exception for negative offset', function () {
    Entity::fromArray([
        'type' => 'hashtag',
        'offset' => -1,
        'length' => 5,
    ]);
})->throws(ValidationException::class, 'minimum: 0');

it('throws exception for zero length', function () {
    Entity::fromArray([
        'type' => 'hashtag',
        'offset' => 0,
        'length' => 0,
    ]);
})->throws(ValidationException::class, 'minimum: 1');
