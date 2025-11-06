<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/tests/Unit/DTO/PhotoTest.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-07
 */

use Telegram\Objects\DTO\Photo;
use Telegram\Objects\Exceptions\ValidationException;

it('can create photo from array with all fields', function () {
    $data = [
        'file_id' => 'AgACAgIAAxkBAAEBGTBhYxKzAAGBAAE',
        'width' => 1280,
        'height' => 720,
        'file_size' => 102400,
    ];

    $photo = Photo::fromArray($data);

    expect($photo->id())->toBe('AgACAgIAAxkBAAEBGTBhYxKzAAGBAAE');
    expect($photo->width())->toBe(1280);
    expect($photo->height())->toBe(720);
    expect($photo->fileSize())->toBe(102400);
});

it('can create photo from array with minimal fields', function () {
    $data = [
        'file_id' => 'AgACAgIAAxkBAAEBGTBhYxKzAAGBAAE',
        'width' => 800,
        'height' => 600,
    ];

    $photo = Photo::fromArray($data);

    expect($photo->id())->toBe('AgACAgIAAxkBAAEBGTBhYxKzAAGBAAE');
    expect($photo->width())->toBe(800);
    expect($photo->height())->toBe(600);
    expect($photo->fileSize())->toBeNull();
});

it('throws exception for missing file_id', function () {
    $data = [
        'width' => 800,
        'height' => 600,
    ];

    expect(fn () => Photo::fromArray($data))
        ->toThrow(ValidationException::class, "Missing required field 'file_id'");
});

it('throws exception for missing width', function () {
    $data = [
        'file_id' => 'AgACAgIAAxkBAAEBGTBhYxKzAAGBAAE',
        'height' => 600,
    ];

    expect(fn () => Photo::fromArray($data))
        ->toThrow(ValidationException::class, "Missing required field 'width'");
});

it('throws exception for missing height', function () {
    $data = [
        'file_id' => 'AgACAgIAAxkBAAEBGTBhYxKzAAGBAAE',
        'width' => 800,
    ];

    expect(fn () => Photo::fromArray($data))
        ->toThrow(ValidationException::class, "Missing required field 'height'");
});

it('throws exception for invalid dimensions', function () {
    expect(fn () => Photo::fromArray([
        'file_id' => 'test',
        'width' => 0,
        'height' => 600,
    ]))->toThrow(ValidationException::class);

    expect(fn () => Photo::fromArray([
        'file_id' => 'test',
        'width' => 800,
        'height' => -1,
    ]))->toThrow(ValidationException::class);
});

it('can calculate aspect ratio and orientation', function () {
    $landscape = Photo::fromArray([
        'file_id' => 'test1',
        'width' => 1920,
        'height' => 1080,
    ]);

    $portrait = Photo::fromArray([
        'file_id' => 'test2',
        'width' => 1080,
        'height' => 1920,
    ]);

    $square = Photo::fromArray([
        'file_id' => 'test3',
        'width' => 1000,
        'height' => 1000,
    ]);

    expect($landscape->aspectRatio())->toBeFloat()->toEqual(1920 / 1080);
    expect($landscape->isLandscape())->toBeTrue();
    expect($landscape->isPortrait())->toBeFalse();
    expect($landscape->isSquare())->toBeFalse();

    expect($portrait->aspectRatio())->toBeFloat()->toEqual(1080 / 1920);
    expect($portrait->isPortrait())->toBeTrue();
    expect($portrait->isLandscape())->toBeFalse();
    expect($portrait->isSquare())->toBeFalse();

    expect($square->aspectRatio())->toBe(1.0);
    expect($square->isSquare())->toBeTrue();
    expect($square->isLandscape())->toBeFalse();
    expect($square->isPortrait())->toBeFalse();
});

it('can convert to array', function () {
    $data = [
        'file_id' => 'AgACAgIAAxkBAAEBGTBhYxKzAAGBAAE',
        'width' => 1280,
        'height' => 720,
        'file_size' => 102400,
    ];

    $photo = Photo::fromArray($data);
    $result = $photo->toArray();

    expect($result)->toBe($data);
});

it('filters null values in toArray', function () {
    $data = [
        'file_id' => 'AgACAgIAAxkBAAEBGTBhYxKzAAGBAAE',
        'width' => 800,
        'height' => 600,
    ];

    $photo = Photo::fromArray($data);
    $result = $photo->toArray();

    expect($result)->toBe($data);
    expect($result)->not->toHaveKey('file_size');
});
