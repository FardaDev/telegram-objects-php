<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/tests/Unit/DTO/InlineQueryResultArticleTest.php
 * Telegraph commit: [commit_hash]
 * Date: [date]
 */

use Telegram\Objects\DTO\InlineQueryResultArticle;
use Telegram\Objects\Exceptions\ValidationException;

it('can create inline query result article from array with minimal fields', function () {
    $result = InlineQueryResultArticle::fromArray([
        'id' => 'article123',
        'type' => 'article',
        'title' => 'Test Article',
        'input_message_content' => [
            'message_text' => 'This is the article content',
        ],
    ]);

    expect($result->id())->toBe('article123');
    expect($result->type())->toBe('article');
    expect($result->title())->toBe('Test Article');
    expect($result->messageText())->toBe('This is the article content');
});

it('can create inline query result article from array with all fields', function () {
    $result = InlineQueryResultArticle::fromArray([
        'id' => 'article456',
        'type' => 'article',
        'title' => 'Complete Article',
        'input_message_content' => [
            'message_text' => 'Full article content',
            'parse_mode' => 'HTML',
        ],
        'reply_markup' => [
            'inline_keyboard' => [
                [['text' => 'Read More', 'url' => 'https://example.com']],
            ],
        ],
        'url' => 'https://article.com',
        'hide_url' => true,
        'description' => 'This is a test article description',
        'thumbnail_url' => 'https://thumb.example.com/image.jpg',
        'thumbnail_width' => 300,
        'thumbnail_height' => 200,
    ]);

    expect($result->id())->toBe('article456');
    expect($result->type())->toBe('article');
    expect($result->title())->toBe('Complete Article');
    expect($result->messageText())->toBe('Full article content');
    expect($result->parseMode())->toBe('HTML');
    expect($result->url())->toBe('https://article.com');
    expect($result->hideUrl())->toBeTrue();
    expect($result->description())->toBe('This is a test article description');
    expect($result->thumbnailUrl())->toBe('https://thumb.example.com/image.jpg');
    expect($result->thumbnailWidth())->toBe(300);
    expect($result->thumbnailHeight())->toBe(200);
});

it('can convert to array', function () {
    $data = [
        'id' => 'article789',
        'type' => 'article',
        'title' => 'Array Test Article',
        'input_message_content' => [
            'message_text' => 'Array test content',
        ],
        'url' => 'https://test.com',
        'description' => 'Test description',
    ];

    $result = InlineQueryResultArticle::fromArray($data);
    $array = $result->toArray();

    expect($array)->toHaveKey('id', 'article789');
    expect($array)->toHaveKey('type', 'article');
    expect($array)->toHaveKey('title', 'Array Test Article');
    // Note: input_message_content is handled internally and not exposed in toArray()
    expect($array)->toHaveKey('url', 'https://test.com');
    expect($array)->toHaveKey('description', 'Test description');
});

it('filters null values in toArray', function () {
    $result = InlineQueryResultArticle::fromArray([
        'id' => 'article123',
        'type' => 'article',
        'title' => 'Simple Article',
        'input_message_content' => [
            'message_text' => 'Simple content',
        ],
    ]);

    $array = $result->toArray();

    expect($array)->toHaveKey('id', 'article123');
    expect($array)->toHaveKey('type', 'article');
    expect($array)->toHaveKey('title', 'Simple Article');
    expect($array)->not->toHaveKey('reply_markup');
    expect($array)->not->toHaveKey('url');
    expect($array)->not->toHaveKey('hide_url');
    expect($array)->not->toHaveKey('description');
    expect($array)->not->toHaveKey('thumbnail_url');
    expect($array)->not->toHaveKey('thumbnail_width');
    expect($array)->not->toHaveKey('thumbnail_height');
});

it('throws exception for missing title', function () {
    InlineQueryResultArticle::fromArray([
        'id' => 'article123',
        'type' => 'article',
        'input_message_content' => [
            'message_text' => 'Content',
        ],
    ]);
})->throws(ValidationException::class, "Missing required field 'title'");

it('can handle missing input_message_content', function () {
    $result = InlineQueryResultArticle::fromArray([
        'id' => 'article123',
        'type' => 'article',
        'title' => 'Test Title',
    ]);

    expect($result->messageText())->toBe('');
    expect($result->parseMode())->toBeNull();
});

it('can handle empty title', function () {
    $result = InlineQueryResultArticle::fromArray([
        'id' => 'article123',
        'type' => 'article',
        'title' => '',
        'input_message_content' => [
            'message_text' => 'Content',
        ],
    ]);

    expect($result->title())->toBe('');
});

it('can handle invalid url', function () {
    $result = InlineQueryResultArticle::fromArray([
        'id' => 'article123',
        'type' => 'article',
        'title' => 'Test Title',
        'input_message_content' => [
            'message_text' => 'Content',
        ],
        'url' => 'not-a-valid-url',
    ]);

    expect($result->url())->toBe('not-a-valid-url');
});

it('can handle invalid thumbnail_url', function () {
    $result = InlineQueryResultArticle::fromArray([
        'id' => 'article123',
        'type' => 'article',
        'title' => 'Test Title',
        'input_message_content' => [
            'message_text' => 'Content',
        ],
        'thumbnail_url' => 'invalid-url',
    ]);

    expect($result->thumbnailUrl())->toBe('invalid-url');
});

it('can handle negative thumbnail dimensions', function () {
    $result = InlineQueryResultArticle::fromArray([
        'id' => 'article123',
        'type' => 'article',
        'title' => 'Test Title',
        'input_message_content' => [
            'message_text' => 'Content',
        ],
        'thumbnail_width' => -100,
    ]);

    expect($result->thumbnailWidth())->toBe(-100);
});

it('can check if article has url', function () {
    $withoutUrl = InlineQueryResultArticle::fromArray([
        'id' => 'article123',
        'type' => 'article',
        'title' => 'No URL Article',
        'input_message_content' => [
            'message_text' => 'Content',
        ],
    ]);

    $withUrl = InlineQueryResultArticle::fromArray([
        'id' => 'article456',
        'type' => 'article',
        'title' => 'With URL Article',
        'input_message_content' => [
            'message_text' => 'Content',
        ],
        'url' => 'https://example.com',
    ]);

    expect($withoutUrl->hasUrl())->toBeFalse();
    expect($withUrl->hasUrl())->toBeTrue();
});

it('can check if article has description', function () {
    $withoutDescription = InlineQueryResultArticle::fromArray([
        'id' => 'article123',
        'type' => 'article',
        'title' => 'No Description',
        'input_message_content' => [
            'message_text' => 'Content',
        ],
    ]);

    $withDescription = InlineQueryResultArticle::fromArray([
        'id' => 'article456',
        'type' => 'article',
        'title' => 'With Description',
        'input_message_content' => [
            'message_text' => 'Content',
        ],
        'description' => 'This article has a description',
    ]);

    expect($withoutDescription->description())->toBeNull();
    expect($withDescription->description())->toBe('This article has a description');
});

it('can check if article has thumbnail', function () {
    $withoutThumb = InlineQueryResultArticle::fromArray([
        'id' => 'article123',
        'type' => 'article',
        'title' => 'No Thumbnail',
        'input_message_content' => [
            'message_text' => 'Content',
        ],
    ]);

    $withThumb = InlineQueryResultArticle::fromArray([
        'id' => 'article456',
        'type' => 'article',
        'title' => 'With Thumbnail',
        'input_message_content' => [
            'message_text' => 'Content',
        ],
        'thumbnail_url' => 'https://example.com/thumb.jpg',
    ]);

    expect($withoutThumb->hasThumbnail())->toBeFalse();
    expect($withThumb->hasThumbnail())->toBeTrue();
});
