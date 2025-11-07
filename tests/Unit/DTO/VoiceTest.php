<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: tests/Unit/DTO/VoiceTest.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-07
 */

use Telegram\Objects\DTO\Voice;
use Telegram\Objects\Exceptions\ValidationException;

it('can create voice from array with minimal fields', function () {
    $voice = Voice::fromArray([
        'file_id' => 'test_voice_id',
        'duration' => 30,
    ]);

    expect($voice->id())->toBe('test_voice_id');
    expect($voice->duration())->toBe(30);
    expect($voice->mimeType())->toBeNull();
    expect($voice->fileSize())->toBeNull();
});

it('can create voice from array with all fields', function () {
    $voice = Voice::fromArray([
        'file_id' => 'test_voice_id',
        'duration' => 30,
        'mime_type' => 'audio/ogg',
        'file_size' => 50000,
    ]);

    expect($voice->id())->toBe('test_voice_id');
    expect($voice->duration())->toBe(30);
    expect($voice->mimeType())->toBe('audio/ogg');
    expect($voice->fileSize())->toBe(50000);
});

it('can convert to array', function () {
    $voice = Voice::fromArray([
        'file_id' => 'test_voice_id',
        'duration' => 30,
        'mime_type' => 'audio/ogg',
        'file_size' => 50000,
    ]);

    $array = $voice->toArray();

    expect($array)->toHaveKey('file_id', 'test_voice_id');
    expect($array)->toHaveKey('duration', 30);
    expect($array)->toHaveKey('mime_type', 'audio/ogg');
    expect($array)->toHaveKey('file_size', 50000);
});

it('filters null values in toArray', function () {
    $voice = Voice::fromArray([
        'file_id' => 'test_voice_id',
        'duration' => 30,
    ]);

    $array = $voice->toArray();

    expect($array)->not->toHaveKey('mime_type');
    expect($array)->not->toHaveKey('file_size');
});

it('can format duration', function () {
    $shortVoice = Voice::fromArray([
        'file_id' => 'test1',
        'duration' => 45, // 0:45
    ]);

    $longVoice = Voice::fromArray([
        'file_id' => 'test2',
        'duration' => 3661, // 1:01:01
    ]);

    expect($shortVoice->formatDuration())->toBe('0:45');
    expect($longVoice->formatDuration())->toBe('1:01:01');
});

it('throws exception for missing file_id', function () {
    Voice::fromArray([
        'duration' => 30,
    ]);
})->throws(ValidationException::class, "Missing required field 'file_id'");

it('throws exception for missing duration', function () {
    Voice::fromArray([
        'file_id' => 'test_voice_id',
    ]);
})->throws(ValidationException::class, "Missing required field 'duration'");

it('throws exception for negative duration', function () {
    Voice::fromArray([
        'file_id' => 'test_voice_id',
        'duration' => -1,
    ]);
})->throws(ValidationException::class, 'minimum: 0');
