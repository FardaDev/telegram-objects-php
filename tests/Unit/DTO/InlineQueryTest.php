<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/tests/Unit/DTO/InlineQueryTest.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-07
 */

use Telegram\Objects\DTO\InlineQuery;
use Telegram\Objects\DTO\Location;
use Telegram\Objects\DTO\User;
use Telegram\Objects\Exceptions\ValidationException;

it('can create inline query from array with minimal fields', function () {
    $inlineQuery = InlineQuery::fromArray([
        'id' => 'inline_query_id',
        'from' => [
            'id' => 123456789,
            'first_name' => 'John',
            'is_bot' => false,
        ],
        'query' => 'search term',
        'offset' => '10',
    ]);

    expect($inlineQuery->id())->toBe('inline_query_id');
    expect($inlineQuery->from())->toBeInstanceOf(User::class);
    expect($inlineQuery->from()->id())->toBe(123456789);
    expect($inlineQuery->query())->toBe('search term');
    expect($inlineQuery->offset())->toBe('10');
    expect($inlineQuery->chatType())->toBeNull();
    expect($inlineQuery->location())->toBeNull();
    expect($inlineQuery->isEmpty())->toBeFalse();
    expect($inlineQuery->hasLocation())->toBeFalse();
    expect($inlineQuery->hasChatType())->toBeFalse();
    expect($inlineQuery->queryLength())->toBe(11);
});

it('can create inline query from array with all fields', function () {
    $inlineQuery = InlineQuery::fromArray([
        'id' => 'inline_query_id',
        'from' => [
            'id' => 123456789,
            'first_name' => 'John',
            'is_bot' => false,
        ],
        'query' => 'search term',
        'offset' => '10',
        'chat_type' => 'private',
        'location' => [
            'latitude' => 40.7128,
            'longitude' => -74.0060,
        ],
    ]);

    expect($inlineQuery->id())->toBe('inline_query_id');
    expect($inlineQuery->from())->toBeInstanceOf(User::class);
    expect($inlineQuery->query())->toBe('search term');
    expect($inlineQuery->offset())->toBe('10');
    expect($inlineQuery->chatType())->toBe('private');
    expect($inlineQuery->location())->toBeInstanceOf(Location::class);
    expect($inlineQuery->isEmpty())->toBeFalse();
    expect($inlineQuery->hasLocation())->toBeTrue();
    expect($inlineQuery->hasChatType())->toBeTrue();
});

it('can handle empty query', function () {
    $inlineQuery = InlineQuery::fromArray([
        'id' => 'inline_query_id',
        'from' => [
            'id' => 123456789,
            'first_name' => 'John',
            'is_bot' => false,
        ],
        'query' => '',
        'offset' => '',
    ]);

    expect($inlineQuery->query())->toBe('');
    expect($inlineQuery->isEmpty())->toBeTrue();
    expect($inlineQuery->queryLength())->toBe(0);
});

it('can convert to array', function () {
    $data = [
        'id' => 'inline_query_id',
        'from' => [
            'id' => 123456789,
            'first_name' => 'John',
            'is_bot' => false,
        ],
        'query' => 'search term',
        'offset' => '10',
        'chat_type' => 'private',
    ];

    $inlineQuery = InlineQuery::fromArray($data);
    $result = $inlineQuery->toArray();

    expect($result)->toHaveKey('id', 'inline_query_id');
    expect($result)->toHaveKey('from');
    expect($result)->toHaveKey('query', 'search term');
    expect($result)->toHaveKey('offset', '10');
    expect($result)->toHaveKey('chat_type', 'private');
});

it('filters null values in toArray', function () {
    $inlineQuery = InlineQuery::fromArray([
        'id' => 'inline_query_id',
        'from' => [
            'id' => 123456789,
            'first_name' => 'John',
            'is_bot' => false,
        ],
        'query' => 'search term',
        'offset' => '10',
    ]);

    $result = $inlineQuery->toArray();

    expect($result)->not->toHaveKey('chat_type');
    expect($result)->not->toHaveKey('location');
});

it('throws exception for missing id', function () {
    InlineQuery::fromArray([
        'from' => [
            'id' => 123456789,
            'first_name' => 'John',
            'is_bot' => false,
        ],
        'query' => 'search term',
        'offset' => '10',
    ]);
})->throws(ValidationException::class, "Missing required field 'id'");

it('throws exception for missing from', function () {
    InlineQuery::fromArray([
        'id' => 'inline_query_id',
        'query' => 'search term',
        'offset' => '10',
    ]);
})->throws(ValidationException::class, "Missing required field 'from'");

it('throws exception for missing query', function () {
    InlineQuery::fromArray([
        'id' => 'inline_query_id',
        'from' => [
            'id' => 123456789,
            'first_name' => 'John',
            'is_bot' => false,
        ],
        'offset' => '10',
    ]);
})->throws(ValidationException::class, "Missing required field 'query'");

it('throws exception for missing offset', function () {
    InlineQuery::fromArray([
        'id' => 'inline_query_id',
        'from' => [
            'id' => 123456789,
            'first_name' => 'John',
            'is_bot' => false,
        ],
        'query' => 'search term',
    ]);
})->throws(ValidationException::class, "Missing required field 'offset'");
