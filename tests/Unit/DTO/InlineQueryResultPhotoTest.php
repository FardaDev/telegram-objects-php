<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: tests/Unit/DTO/InlineQueryResultPhotoTest.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-07
 */

use Telegram\Objects\DTO\InlineQueryResultPhoto;
use Telegram\Objects\Exceptions\ValidationException;

it('can create inline query result photo from array with minimal fields', function () {
    $result = InlineQueryResultPhoto::fromArray([
        'id' => 'photo123',
        'type' => 'photo',
        'photo_url' => 'https://example.com/photo.jpg',
        'thumbnail_url' => 'https://example.com/thumb.jpg',
    ]);

    expect($result->id())->toBe('photo123');
    expect($result->type())->toBe('photo');
    expect($result->photoUrl())->toBe('https://example.com/photo.jpg');
    expect($result->thumbnailUrl())->toBe('https://example.com/thumb.jpg');
});

it('can create inline query result photo from array with all fields', function () {
    $result = InlineQueryResultPhoto::fromArray([
        'id' => 'photo456',
        'type' => 'photo',
        'photo_url' => 'https://example.com/full.jpg',
        'thumbnail_url' => 'https://example.com/thumbnail.jpg',
        'photo_width' => 800,
        'photo_height' => 600,
        'title' => 'Beautiful Photo',
        'description' => 'A stunning landscape photograph',
        'caption' => 'Sunset over mountains',
        'parse_mode' => 'HTML',
    ]);

    expect($result->id())->toBe('photo456');
    expect($result->type())->toBe('photo');
    expect($result->photoUrl())->toBe('https://example.com/full.jpg');
    expect($result->thumbnailUrl())->toBe('https://example.com/thumbnail.jpg');
    expect($result->photoWidth())->toBe(800);
    expect($result->photoHeight())->toBe(600);
    expect($result->title())->toBe('Beautiful Photo');
    expect($result->description())->toBe('A stunning landscape photograph');
    expect($result->caption())->toBe('Sunset over mountains');
    expect($result->parseMode())->toBe('HTML');
});

it('can convert to array', function () {
    $data = [
        'id' => 'photo789',
        'type' => 'photo',
        'photo_url' => 'https://test.com/image.jpg',
        'thumbnail_url' => 'https://test.com/thumb.jpg',
        'photo_width' => 400,
        'photo_height' => 300,
        'title' => 'Test Photo',
        'caption' => 'Test caption',
    ];

    $result = InlineQueryResultPhoto::fromArray($data);
    $array = $result->toArray();

    expect($array)->toHaveKey('id', 'photo789');
    expect($array)->toHaveKey('type', 'photo');
    expect($array)->toHaveKey('photo_url', 'https://test.com/image.jpg');
    expect($array)->toHaveKey('thumbnail_url', 'https://test.com/thumb.jpg');
    expect($array)->toHaveKey('photo_width', 400);
    expect($array)->toHaveKey('photo_height', 300);
    expect($array)->toHaveKey('title', 'Test Photo');
    expect($array)->toHaveKey('caption', 'Test caption');
});

it('filters null values in toArray', function () {
    $result = InlineQueryResultPhoto::fromArray([
        'id' => 'photo123',
        'type' => 'photo',
        'photo_url' => 'https://example.com/photo.jpg',
        'thumbnail_url' => 'https://example.com/thumb.jpg',
    ]);

    $array = $result->toArray();

    expect($array)->toHaveKey('id', 'photo123');
    expect($array)->toHaveKey('type', 'photo');
    expect($array)->toHaveKey('photo_url', 'https://example.com/photo.jpg');
    expect($array)->toHaveKey('thumbnail_url', 'https://example.com/thumb.jpg');
    expect($array)->not->toHaveKey('photo_width');
    expect($array)->not->toHaveKey('photo_height');
    expect($array)->not->toHaveKey('title');
    expect($array)->not->toHaveKey('description');
    expect($array)->not->toHaveKey('caption');
    expect($array)->not->toHaveKey('parse_mode');
});

it('throws exception for missing photo_url', function () {
    InlineQueryResultPhoto::fromArray([
        'id' => 'photo123',
        'type' => 'photo',
        'thumbnail_url' => 'https://example.com/thumb.jpg',
    ]);
})->throws(ValidationException::class, "Missing required field 'photo_url'");

it('throws exception for missing thumbnail_url', function () {
    InlineQueryResultPhoto::fromArray([
        'id' => 'photo123',
        'type' => 'photo',
        'photo_url' => 'https://example.com/photo.jpg',
    ]);
})->throws(ValidationException::class, "Missing required field 'thumbnail_url'");

it('can check if photo has dimensions', function () {
    $withoutDimensions = InlineQueryResultPhoto::fromArray([
        'id' => 'photo123',
        'type' => 'photo',
        'photo_url' => 'https://example.com/photo.jpg',
        'thumbnail_url' => 'https://example.com/thumb.jpg',
    ]);

    $withDimensions = InlineQueryResultPhoto::fromArray([
        'id' => 'photo456',
        'type' => 'photo',
        'photo_url' => 'https://example.com/photo.jpg',
        'thumbnail_url' => 'https://example.com/thumb.jpg',
        'photo_width' => 800,
        'photo_height' => 600,
    ]);

    expect($withoutDimensions->photoWidth())->toBeNull();
    expect($withoutDimensions->photoHeight())->toBeNull();
    expect($withDimensions->photoWidth())->toBe(800);
    expect($withDimensions->photoHeight())->toBe(600);
});

it('can check if photo has title', function () {
    $withoutTitle = InlineQueryResultPhoto::fromArray([
        'id' => 'photo123',
        'type' => 'photo',
        'photo_url' => 'https://example.com/photo.jpg',
        'thumbnail_url' => 'https://example.com/thumb.jpg',
    ]);

    $withTitle = InlineQueryResultPhoto::fromArray([
        'id' => 'photo456',
        'type' => 'photo',
        'photo_url' => 'https://example.com/photo.jpg',
        'thumbnail_url' => 'https://example.com/thumb.jpg',
        'title' => 'Beautiful Photo',
    ]);

    expect($withoutTitle->title())->toBeNull();
    expect($withTitle->title())->toBe('Beautiful Photo');
});

it('can check if photo has description', function () {
    $withoutDescription = InlineQueryResultPhoto::fromArray([
        'id' => 'photo123',
        'type' => 'photo',
        'photo_url' => 'https://example.com/photo.jpg',
        'thumbnail_url' => 'https://example.com/thumb.jpg',
    ]);

    $withDescription = InlineQueryResultPhoto::fromArray([
        'id' => 'photo456',
        'type' => 'photo',
        'photo_url' => 'https://example.com/photo.jpg',
        'thumbnail_url' => 'https://example.com/thumb.jpg',
        'description' => 'A beautiful landscape',
    ]);

    expect($withoutDescription->description())->toBeNull();
    expect($withDescription->description())->toBe('A beautiful landscape');
});

it('can check if photo has caption', function () {
    $withoutCaption = InlineQueryResultPhoto::fromArray([
        'id' => 'photo123',
        'type' => 'photo',
        'photo_url' => 'https://example.com/photo.jpg',
        'thumbnail_url' => 'https://example.com/thumb.jpg',
    ]);

    $withCaption = InlineQueryResultPhoto::fromArray([
        'id' => 'photo456',
        'type' => 'photo',
        'photo_url' => 'https://example.com/photo.jpg',
        'thumbnail_url' => 'https://example.com/thumb.jpg',
        'caption' => 'Sunset over mountains',
    ]);

    expect($withoutCaption->caption())->toBeNull();
    expect($withCaption->caption())->toBe('Sunset over mountains');
});
