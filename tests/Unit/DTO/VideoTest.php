<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: tests/Unit/DTO/VideoTest.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-07
 */

use Telegram\Objects\DTO\Photo;
use Telegram\Objects\DTO\Video;
use Telegram\Objects\Exceptions\ValidationException;

it('can create video from array with minimal fields', function () {
    $video = Video::fromArray([
        'file_id' => 'test_video_id',
        'width' => 1920,
        'height' => 1080,
        'duration' => 120,
    ]);

    expect($video->id())->toBe('test_video_id');
    expect($video->width())->toBe(1920);
    expect($video->height())->toBe(1080);
    expect($video->duration())->toBe(120);
    expect($video->fileName())->toBeNull();
    expect($video->mimeType())->toBeNull();
    expect($video->fileSize())->toBeNull();
    expect($video->thumbnail())->toBeNull();
});

it('can create video from array with all fields', function () {
    $video = Video::fromArray([
        'file_id' => 'test_video_id',
        'width' => 1920,
        'height' => 1080,
        'duration' => 120,
        'file_name' => 'test_video.mp4',
        'mime_type' => 'video/mp4',
        'file_size' => 1024000,
        'thumbnail' => [
            'file_id' => 'thumb_id',
            'width' => 320,
            'height' => 180,
        ],
    ]);

    expect($video->id())->toBe('test_video_id');
    expect($video->width())->toBe(1920);
    expect($video->height())->toBe(1080);
    expect($video->duration())->toBe(120);
    expect($video->fileName())->toBe('test_video.mp4');
    expect($video->mimeType())->toBe('video/mp4');
    expect($video->fileSize())->toBe(1024000);
    expect($video->thumbnail())->toBeInstanceOf(Photo::class);
    expect($video->hasThumbnail())->toBeTrue();
});

it('can convert to array', function () {
    $video = Video::fromArray([
        'file_id' => 'test_video_id',
        'width' => 1920,
        'height' => 1080,
        'duration' => 120,
        'file_name' => 'test_video.mp4',
        'mime_type' => 'video/mp4',
        'file_size' => 1024000,
    ]);

    $array = $video->toArray();

    expect($array)->toHaveKey('file_id', 'test_video_id');
    expect($array)->toHaveKey('width', 1920);
    expect($array)->toHaveKey('height', 1080);
    expect($array)->toHaveKey('duration', 120);
    expect($array)->toHaveKey('file_name', 'test_video.mp4');
    expect($array)->toHaveKey('mime_type', 'video/mp4');
    expect($array)->toHaveKey('file_size', 1024000);
});

it('filters null values in toArray', function () {
    $video = Video::fromArray([
        'file_id' => 'test_video_id',
        'width' => 1920,
        'height' => 1080,
        'duration' => 120,
    ]);

    $array = $video->toArray();

    expect($array)->not->toHaveKey('file_name');
    expect($array)->not->toHaveKey('mime_type');
    expect($array)->not->toHaveKey('file_size');
    expect($array)->not->toHaveKey('thumbnail');
});

it('can calculate aspect ratio and orientation', function () {
    $landscape = Video::fromArray([
        'file_id' => 'test1',
        'width' => 1920,
        'height' => 1080,
        'duration' => 120,
    ]);

    $portrait = Video::fromArray([
        'file_id' => 'test2',
        'width' => 1080,
        'height' => 1920,
        'duration' => 120,
    ]);

    $square = Video::fromArray([
        'file_id' => 'test3',
        'width' => 1000,
        'height' => 1000,
        'duration' => 120,
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

it('can format duration', function () {
    $shortVideo = Video::fromArray([
        'file_id' => 'test1',
        'width' => 1920,
        'height' => 1080,
        'duration' => 90, // 1:30
    ]);

    $longVideo = Video::fromArray([
        'file_id' => 'test2',
        'width' => 1920,
        'height' => 1080,
        'duration' => 3661, // 1:01:01
    ]);

    expect($shortVideo->formatDuration())->toBe('1:30');
    expect($longVideo->formatDuration())->toBe('1:01:01');
});

it('throws exception for missing file_id', function () {
    Video::fromArray([
        'width' => 1920,
        'height' => 1080,
        'duration' => 120,
    ]);
})->throws(ValidationException::class, "Missing required field 'file_id'");

it('throws exception for missing width', function () {
    Video::fromArray([
        'file_id' => 'test_video_id',
        'height' => 1080,
        'duration' => 120,
    ]);
})->throws(ValidationException::class, "Missing required field 'width'");

it('throws exception for missing height', function () {
    Video::fromArray([
        'file_id' => 'test_video_id',
        'width' => 1920,
        'duration' => 120,
    ]);
})->throws(ValidationException::class, "Missing required field 'height'");

it('throws exception for missing duration', function () {
    Video::fromArray([
        'file_id' => 'test_video_id',
        'width' => 1920,
        'height' => 1080,
    ]);
})->throws(ValidationException::class, "Missing required field 'duration'");

it('throws exception for invalid dimensions', function () {
    Video::fromArray([
        'file_id' => 'test_video_id',
        'width' => 0,
        'height' => 1080,
        'duration' => 120,
    ]);
})->throws(ValidationException::class, 'minimum: 1');
