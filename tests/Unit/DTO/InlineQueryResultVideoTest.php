<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: tests/Unit/DTO/InlineQueryResultVideoTest.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-07
 */

use Telegram\Objects\DTO\InlineQueryResultVideo;
use Telegram\Objects\Exceptions\ValidationException;

it('can create inline query result video from array with minimal fields', function () {
    $result = InlineQueryResultVideo::fromArray([
        'id' => 'video123',
        'type' => 'video',
        'video_url' => 'https://example.com/video.mp4',
        'mime_type' => 'video/mp4',
        'thumbnail_url' => 'https://example.com/thumb.jpg',
        'title' => 'Test Video',
    ]);

    expect($result->id())->toBe('video123');
    expect($result->type())->toBe('video');
    expect($result->videoUrl())->toBe('https://example.com/video.mp4');
    expect($result->mimeType())->toBe('video/mp4');
    expect($result->thumbnailUrl())->toBe('https://example.com/thumb.jpg');
    expect($result->title())->toBe('Test Video');
});

it('can create inline query result video from array with all fields', function () {
    $result = InlineQueryResultVideo::fromArray([
        'id' => 'video456',
        'type' => 'video',
        'video_url' => 'https://example.com/full-video.mp4',
        'mime_type' => 'video/mp4',
        'thumbnail_url' => 'https://example.com/thumbnail.jpg',
        'title' => 'Complete Video',
        'caption' => 'Amazing video content',
        'parse_mode' => 'HTML',
        'video_width' => 1920,
        'video_height' => 1080,
        'video_duration' => 300,
        'description' => 'A comprehensive video tutorial',
    ]);

    expect($result->id())->toBe('video456');
    expect($result->type())->toBe('video');
    expect($result->videoUrl())->toBe('https://example.com/full-video.mp4');
    expect($result->mimeType())->toBe('video/mp4');
    expect($result->thumbnailUrl())->toBe('https://example.com/thumbnail.jpg');
    expect($result->title())->toBe('Complete Video');
    expect($result->caption())->toBe('Amazing video content');
    expect($result->parseMode())->toBe('HTML');
    expect($result->videoWidth())->toBe(1920);
    expect($result->videoHeight())->toBe(1080);
    expect($result->videoDuration())->toBe(300);
    expect($result->description())->toBe('A comprehensive video tutorial');
});

it('can convert to array', function () {
    $data = [
        'id' => 'video789',
        'type' => 'video',
        'video_url' => 'https://test.com/video.mp4',
        'mime_type' => 'video/mp4',
        'thumbnail_url' => 'https://test.com/thumb.jpg',
        'title' => 'Test Video',
        'video_width' => 800,
        'video_height' => 600,
        'video_duration' => 120,
        'caption' => 'Test caption',
    ];

    $result = InlineQueryResultVideo::fromArray($data);
    $array = $result->toArray();

    expect($array)->toHaveKey('id', 'video789');
    expect($array)->toHaveKey('type', 'video');
    expect($array)->toHaveKey('video_url', 'https://test.com/video.mp4');
    expect($array)->toHaveKey('mime_type', 'video/mp4');
    expect($array)->toHaveKey('thumbnail_url', 'https://test.com/thumb.jpg');
    expect($array)->toHaveKey('title', 'Test Video');
    expect($array)->toHaveKey('video_width', 800);
    expect($array)->toHaveKey('video_height', 600);
    expect($array)->toHaveKey('video_duration', 120);
    expect($array)->toHaveKey('caption', 'Test caption');
});

it('filters null values in toArray', function () {
    $result = InlineQueryResultVideo::fromArray([
        'id' => 'video123',
        'type' => 'video',
        'video_url' => 'https://example.com/video.mp4',
        'mime_type' => 'video/mp4',
        'thumbnail_url' => 'https://example.com/thumb.jpg',
        'title' => 'Simple Video',
    ]);

    $array = $result->toArray();

    expect($array)->toHaveKey('id', 'video123');
    expect($array)->toHaveKey('type', 'video');
    expect($array)->toHaveKey('video_url', 'https://example.com/video.mp4');
    expect($array)->toHaveKey('mime_type', 'video/mp4');
    expect($array)->toHaveKey('thumbnail_url', 'https://example.com/thumb.jpg');
    expect($array)->toHaveKey('title', 'Simple Video');
    expect($array)->not->toHaveKey('caption');
    expect($array)->not->toHaveKey('parse_mode');
    expect($array)->not->toHaveKey('video_width');
    expect($array)->not->toHaveKey('video_height');
    expect($array)->not->toHaveKey('video_duration');
    expect($array)->not->toHaveKey('description');
});

it('throws exception for missing video_url', function () {
    InlineQueryResultVideo::fromArray([
        'id' => 'video123',
        'type' => 'video',
        'mime_type' => 'video/mp4',
        'thumbnail_url' => 'https://example.com/thumb.jpg',
        'title' => 'Test Video',
    ]);
})->throws(ValidationException::class, "Missing required field 'video_url'");

it('throws exception for missing mime_type', function () {
    InlineQueryResultVideo::fromArray([
        'id' => 'video123',
        'type' => 'video',
        'video_url' => 'https://example.com/video.mp4',
        'thumbnail_url' => 'https://example.com/thumb.jpg',
        'title' => 'Test Video',
    ]);
})->throws(ValidationException::class, "Missing required field 'mime_type'");

it('throws exception for missing thumbnail_url', function () {
    InlineQueryResultVideo::fromArray([
        'id' => 'video123',
        'type' => 'video',
        'video_url' => 'https://example.com/video.mp4',
        'mime_type' => 'video/mp4',
        'title' => 'Test Video',
    ]);
})->throws(ValidationException::class, "Missing required field 'thumbnail_url'");

it('throws exception for missing title', function () {
    InlineQueryResultVideo::fromArray([
        'id' => 'video123',
        'type' => 'video',
        'video_url' => 'https://example.com/video.mp4',
        'mime_type' => 'video/mp4',
        'thumbnail_url' => 'https://example.com/thumb.jpg',
    ]);
})->throws(ValidationException::class, "Missing required field 'title'");

it('can check if video has dimensions', function () {
    $withoutDimensions = InlineQueryResultVideo::fromArray([
        'id' => 'video123',
        'type' => 'video',
        'video_url' => 'https://example.com/video.mp4',
        'mime_type' => 'video/mp4',
        'thumbnail_url' => 'https://example.com/thumb.jpg',
        'title' => 'Test Video',
    ]);

    $withDimensions = InlineQueryResultVideo::fromArray([
        'id' => 'video456',
        'type' => 'video',
        'video_url' => 'https://example.com/video.mp4',
        'mime_type' => 'video/mp4',
        'thumbnail_url' => 'https://example.com/thumb.jpg',
        'title' => 'Test Video',
        'video_width' => 1920,
        'video_height' => 1080,
    ]);

    expect($withoutDimensions->videoWidth())->toBeNull();
    expect($withoutDimensions->videoHeight())->toBeNull();
    expect($withDimensions->videoWidth())->toBe(1920);
    expect($withDimensions->videoHeight())->toBe(1080);
});

it('can check if video has duration', function () {
    $withoutDuration = InlineQueryResultVideo::fromArray([
        'id' => 'video123',
        'type' => 'video',
        'video_url' => 'https://example.com/video.mp4',
        'mime_type' => 'video/mp4',
        'thumbnail_url' => 'https://example.com/thumb.jpg',
        'title' => 'Test Video',
    ]);

    $withDuration = InlineQueryResultVideo::fromArray([
        'id' => 'video456',
        'type' => 'video',
        'video_url' => 'https://example.com/video.mp4',
        'mime_type' => 'video/mp4',
        'thumbnail_url' => 'https://example.com/thumb.jpg',
        'title' => 'Test Video',
        'video_duration' => 300,
    ]);

    expect($withoutDuration->videoDuration())->toBeNull();
    expect($withDuration->videoDuration())->toBe(300);
});

it('can check if video has caption', function () {
    $withoutCaption = InlineQueryResultVideo::fromArray([
        'id' => 'video123',
        'type' => 'video',
        'video_url' => 'https://example.com/video.mp4',
        'mime_type' => 'video/mp4',
        'thumbnail_url' => 'https://example.com/thumb.jpg',
        'title' => 'Test Video',
    ]);

    $withCaption = InlineQueryResultVideo::fromArray([
        'id' => 'video456',
        'type' => 'video',
        'video_url' => 'https://example.com/video.mp4',
        'mime_type' => 'video/mp4',
        'thumbnail_url' => 'https://example.com/thumb.jpg',
        'title' => 'Test Video',
        'caption' => 'Amazing video content',
    ]);

    expect($withoutCaption->caption())->toBeNull();
    expect($withCaption->caption())->toBe('Amazing video content');
});

it('can check if video has description', function () {
    $withoutDescription = InlineQueryResultVideo::fromArray([
        'id' => 'video123',
        'type' => 'video',
        'video_url' => 'https://example.com/video.mp4',
        'mime_type' => 'video/mp4',
        'thumbnail_url' => 'https://example.com/thumb.jpg',
        'title' => 'Test Video',
    ]);

    $withDescription = InlineQueryResultVideo::fromArray([
        'id' => 'video456',
        'type' => 'video',
        'video_url' => 'https://example.com/video.mp4',
        'mime_type' => 'video/mp4',
        'thumbnail_url' => 'https://example.com/thumb.jpg',
        'title' => 'Test Video',
        'description' => 'A comprehensive tutorial',
    ]);

    expect($withoutDescription->description())->toBeNull();
    expect($withDescription->description())->toBe('A comprehensive tutorial');
});
