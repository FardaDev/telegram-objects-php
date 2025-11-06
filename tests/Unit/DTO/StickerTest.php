<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/tests/Unit/DTO/StickerTest.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-07
 */

use Telegram\Objects\DTO\Photo;
use Telegram\Objects\DTO\Sticker;
use Telegram\Objects\Exceptions\ValidationException;

it('can create sticker from array with minimal fields', function () {
    $sticker = Sticker::fromArray([
        'file_id' => 'test_sticker_id',
        'type' => 'regular',
        'width' => 512,
        'height' => 512,
        'is_animated' => false,
        'is_video' => false,
    ]);

    expect($sticker->id())->toBe('test_sticker_id');
    expect($sticker->type())->toBe('regular');
    expect($sticker->width())->toBe(512);
    expect($sticker->height())->toBe(512);
    expect($sticker->isAnimated())->toBeFalse();
    expect($sticker->isVideo())->toBeFalse();
    expect($sticker->thumbnail())->toBeNull();
    expect($sticker->emoji())->toBeNull();
    expect($sticker->setName())->toBeNull();
    expect($sticker->fileSize())->toBeNull();
    expect($sticker->needsRepainting())->toBeFalse();
    expect($sticker->isRegular())->toBeTrue();
    expect($sticker->isMask())->toBeFalse();
    expect($sticker->isCustomEmoji())->toBeFalse();
});

it('can create sticker from array with all fields', function () {
    $sticker = Sticker::fromArray([
        'file_id' => 'test_sticker_id',
        'type' => 'mask',
        'width' => 512,
        'height' => 512,
        'is_animated' => true,
        'is_video' => false,
        'thumbnail' => [
            'file_id' => 'thumb_id',
            'width' => 128,
            'height' => 128,
        ],
        'emoji' => '😀',
        'set_name' => 'my_sticker_set',
        'file_size' => 50000,
        'needs_repainting' => true,
    ]);

    expect($sticker->id())->toBe('test_sticker_id');
    expect($sticker->type())->toBe('mask');
    expect($sticker->width())->toBe(512);
    expect($sticker->height())->toBe(512);
    expect($sticker->isAnimated())->toBeTrue();
    expect($sticker->isVideo())->toBeFalse();
    expect($sticker->thumbnail())->toBeInstanceOf(Photo::class);
    expect($sticker->emoji())->toBe('😀');
    expect($sticker->setName())->toBe('my_sticker_set');
    expect($sticker->fileSize())->toBe(50000);
    expect($sticker->needsRepainting())->toBeTrue();
    expect($sticker->hasThumbnail())->toBeTrue();
    expect($sticker->isRegular())->toBeFalse();
    expect($sticker->isMask())->toBeTrue();
    expect($sticker->isCustomEmoji())->toBeFalse();
});

it('can create custom emoji sticker', function () {
    $sticker = Sticker::fromArray([
        'file_id' => 'test_sticker_id',
        'type' => 'custom_emoji',
        'width' => 100,
        'height' => 100,
        'is_animated' => false,
        'is_video' => true,
        'emoji' => '🎉',
    ]);

    expect($sticker->type())->toBe('custom_emoji');
    expect($sticker->isVideo())->toBeTrue();
    expect($sticker->emoji())->toBe('🎉');
    expect($sticker->isRegular())->toBeFalse();
    expect($sticker->isMask())->toBeFalse();
    expect($sticker->isCustomEmoji())->toBeTrue();
});

it('can convert to array', function () {
    $sticker = Sticker::fromArray([
        'file_id' => 'test_sticker_id',
        'type' => 'regular',
        'width' => 512,
        'height' => 512,
        'is_animated' => true,
        'is_video' => false,
        'emoji' => '😀',
        'set_name' => 'my_sticker_set',
        'file_size' => 50000,
        'needs_repainting' => true,
    ]);

    $array = $sticker->toArray();

    expect($array)->toHaveKey('file_id', 'test_sticker_id');
    expect($array)->toHaveKey('type', 'regular');
    expect($array)->toHaveKey('width', 512);
    expect($array)->toHaveKey('height', 512);
    expect($array)->toHaveKey('is_animated', true);
    expect($array)->toHaveKey('is_video', false);
    expect($array)->toHaveKey('emoji', '😀');
    expect($array)->toHaveKey('set_name', 'my_sticker_set');
    expect($array)->toHaveKey('file_size', 50000);
    expect($array)->toHaveKey('needs_repainting', true);
});

it('filters null values in toArray', function () {
    $sticker = Sticker::fromArray([
        'file_id' => 'test_sticker_id',
        'type' => 'regular',
        'width' => 512,
        'height' => 512,
        'is_animated' => false,
        'is_video' => false,
    ]);

    $array = $sticker->toArray();

    expect($array)->not->toHaveKey('thumbnail');
    expect($array)->not->toHaveKey('emoji');
    expect($array)->not->toHaveKey('set_name');
    expect($array)->not->toHaveKey('file_size');
    expect($array)->not->toHaveKey('needs_repainting');
});

it('can calculate aspect ratio', function () {
    $sticker = Sticker::fromArray([
        'file_id' => 'test_sticker_id',
        'type' => 'regular',
        'width' => 512,
        'height' => 256,
        'is_animated' => false,
        'is_video' => false,
    ]);

    expect($sticker->aspectRatio())->toBeFloat()->toEqual(2.0);
});

it('throws exception for missing file_id', function () {
    Sticker::fromArray([
        'type' => 'regular',
        'width' => 512,
        'height' => 512,
        'is_animated' => false,
        'is_video' => false,
    ]);
})->throws(ValidationException::class, "Missing required field 'file_id'");

it('throws exception for missing type', function () {
    Sticker::fromArray([
        'file_id' => 'test_sticker_id',
        'width' => 512,
        'height' => 512,
        'is_animated' => false,
        'is_video' => false,
    ]);
})->throws(ValidationException::class, "Missing required field 'type'");

it('throws exception for invalid type', function () {
    Sticker::fromArray([
        'file_id' => 'test_sticker_id',
        'type' => 'invalid_type',
        'width' => 512,
        'height' => 512,
        'is_animated' => false,
        'is_video' => false,
    ]);
})->throws(InvalidArgumentException::class, 'Invalid sticker type: invalid_type. Must be one of: regular, mask, custom_emoji');

it('throws exception for missing width', function () {
    Sticker::fromArray([
        'file_id' => 'test_sticker_id',
        'type' => 'regular',
        'height' => 512,
        'is_animated' => false,
        'is_video' => false,
    ]);
})->throws(ValidationException::class, "Missing required field 'width'");

it('throws exception for missing height', function () {
    Sticker::fromArray([
        'file_id' => 'test_sticker_id',
        'type' => 'regular',
        'width' => 512,
        'is_animated' => false,
        'is_video' => false,
    ]);
})->throws(ValidationException::class, "Missing required field 'height'");

it('throws exception for missing is_animated', function () {
    Sticker::fromArray([
        'file_id' => 'test_sticker_id',
        'type' => 'regular',
        'width' => 512,
        'height' => 512,
        'is_video' => false,
    ]);
})->throws(ValidationException::class, "Missing required field 'is_animated'");

it('throws exception for missing is_video', function () {
    Sticker::fromArray([
        'file_id' => 'test_sticker_id',
        'type' => 'regular',
        'width' => 512,
        'height' => 512,
        'is_animated' => false,
    ]);
})->throws(ValidationException::class, "Missing required field 'is_video'");

it('throws exception for invalid dimensions', function () {
    Sticker::fromArray([
        'file_id' => 'test_sticker_id',
        'type' => 'regular',
        'width' => 0,
        'height' => 512,
        'is_animated' => false,
        'is_video' => false,
    ]);
})->throws(ValidationException::class, 'minimum: 1');
