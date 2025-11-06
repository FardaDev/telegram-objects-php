<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/tests/Unit/DTO/AnimationTest.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-07
 */

use Telegram\Objects\DTO\Animation;
use Telegram\Objects\DTO\Photo;
use Telegram\Objects\Exceptions\ValidationException;

it('can create animation from array with minimal fields', function () {
    $animation = Animation::fromArray([
        'file_id' => 'test_animation_id',
        'width' => 480,
        'height' => 360,
        'duration' => 5,
    ]);

    expect($animation->id())->toBe('test_animation_id');
    expect($animation->width())->toBe(480);
    expect($animation->height())->toBe(360);
    expect($animation->duration())->toBe(5);
    expect($animation->fileName())->toBeNull();
    expect($animation->mimeType())->toBeNull();
    expect($animation->fileSize())->toBeNull();
    expect($animation->thumbnail())->toBeNull();
});

it('can create animation from array with all fields', function () {
    $animation = Animation::fromArray([
        'file_id' => 'test_animation_id',
        'width' => 480,
        'height' => 360,
        'duration' => 5,
        'file_name' => 'animation.gif',
        'mime_type' => 'image/gif',
        'file_size' => 256000,
        'thumbnail' => [
            'file_id' => 'thumb_id',
            'width' => 120,
            'height' => 90,
        ],
    ]);

    expect($animation->id())->toBe('test_animation_id');
    expect($animation->width())->toBe(480);
    expect($animation->height())->toBe(360);
    expect($animation->duration())->toBe(5);
    expect($animation->fileName())->toBe('animation.gif');
    expect($animation->mimeType())->toBe('image/gif');
    expect($animation->fileSize())->toBe(256000);
    expect($animation->thumbnail())->toBeInstanceOf(Photo::class);
    expect($animation->hasThumbnail())->toBeTrue();
});

it('can convert to array', function () {
    $animation = Animation::fromArray([
        'file_id' => 'test_animation_id',
        'width' => 480,
        'height' => 360,
        'duration' => 5,
        'file_name' => 'animation.gif',
        'mime_type' => 'image/gif',
        'file_size' => 256000,
    ]);

    $array = $animation->toArray();

    expect($array)->toHaveKey('file_id', 'test_animation_id');
    expect($array)->toHaveKey('width', 480);
    expect($array)->toHaveKey('height', 360);
    expect($array)->toHaveKey('duration', 5);
    expect($array)->toHaveKey('file_name', 'animation.gif');
    expect($array)->toHaveKey('mime_type', 'image/gif');
    expect($array)->toHaveKey('file_size', 256000);
});

it('filters null values in toArray', function () {
    $animation = Animation::fromArray([
        'file_id' => 'test_animation_id',
        'width' => 480,
        'height' => 360,
        'duration' => 5,
    ]);

    $array = $animation->toArray();

    expect($array)->not->toHaveKey('file_name');
    expect($array)->not->toHaveKey('mime_type');
    expect($array)->not->toHaveKey('file_size');
    expect($array)->not->toHaveKey('thumbnail');
});

it('can calculate aspect ratio and orientation', function () {
    $landscape = Animation::fromArray([
        'file_id' => 'test1',
        'width' => 640,
        'height' => 360,
        'duration' => 5,
    ]);

    $portrait = Animation::fromArray([
        'file_id' => 'test2',
        'width' => 360,
        'height' => 640,
        'duration' => 5,
    ]);

    $square = Animation::fromArray([
        'file_id' => 'test3',
        'width' => 400,
        'height' => 400,
        'duration' => 5,
    ]);

    expect($landscape->aspectRatio())->toBeFloat()->toEqual(640 / 360);
    expect($landscape->isLandscape())->toBeTrue();
    expect($landscape->isPortrait())->toBeFalse();
    expect($landscape->isSquare())->toBeFalse();

    expect($portrait->aspectRatio())->toBeFloat()->toEqual(360 / 640);
    expect($portrait->isPortrait())->toBeTrue();
    expect($portrait->isLandscape())->toBeFalse();
    expect($portrait->isSquare())->toBeFalse();

    expect($square->aspectRatio())->toBe(1.0);
    expect($square->isSquare())->toBeTrue();
    expect($square->isLandscape())->toBeFalse();
    expect($square->isPortrait())->toBeFalse();
});

it('can format duration', function () {
    $shortAnimation = Animation::fromArray([
        'file_id' => 'test1',
        'width' => 480,
        'height' => 360,
        'duration' => 3, // 0:03
    ]);

    $longAnimation = Animation::fromArray([
        'file_id' => 'test2',
        'width' => 480,
        'height' => 360,
        'duration' => 125, // 2:05
    ]);

    expect($shortAnimation->formatDuration())->toBe('0:03');
    expect($longAnimation->formatDuration())->toBe('2:05');
});

it('throws exception for missing file_id', function () {
    Animation::fromArray([
        'width' => 480,
        'height' => 360,
        'duration' => 5,
    ]);
})->throws(ValidationException::class, "Missing required field 'file_id'");

it('throws exception for missing width', function () {
    Animation::fromArray([
        'file_id' => 'test_animation_id',
        'height' => 360,
        'duration' => 5,
    ]);
})->throws(ValidationException::class, "Missing required field 'width'");

it('throws exception for missing height', function () {
    Animation::fromArray([
        'file_id' => 'test_animation_id',
        'width' => 480,
        'duration' => 5,
    ]);
})->throws(ValidationException::class, "Missing required field 'height'");

it('throws exception for missing duration', function () {
    Animation::fromArray([
        'file_id' => 'test_animation_id',
        'width' => 480,
        'height' => 360,
    ]);
})->throws(ValidationException::class, "Missing required field 'duration'");

it('throws exception for invalid dimensions', function () {
    Animation::fromArray([
        'file_id' => 'test_animation_id',
        'width' => 0,
        'height' => 360,
        'duration' => 5,
    ]);
})->throws(ValidationException::class, 'minimum: 1');
