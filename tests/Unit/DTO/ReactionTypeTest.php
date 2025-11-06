<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/tests/Unit/DTO/ReactionTypeTest.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-07
 */

use Telegram\Objects\DTO\ReactionType;
use Telegram\Objects\Exceptions\ValidationException;

it('can create emoji reaction type from array', function () {
    $data = [
        'type' => ReactionType::TYPE_EMOJI,
        'emoji' => '👍',
    ];

    $reaction = ReactionType::fromArray($data);

    expect($reaction->type())->toBe(ReactionType::TYPE_EMOJI);
    expect($reaction->emoji())->toBe('👍');
    expect($reaction->customEmojiId())->toBeNull();
    expect($reaction->isEmoji())->toBeTrue();
    expect($reaction->isCustomEmoji())->toBeFalse();
    expect($reaction->isPaidEmoji())->toBeFalse();
});

it('can create custom emoji reaction type from array', function () {
    $data = [
        'type' => ReactionType::TYPE_CUSTOM_EMOJI,
        'custom_emoji_id' => 'custom123',
    ];

    $reaction = ReactionType::fromArray($data);

    expect($reaction->type())->toBe(ReactionType::TYPE_CUSTOM_EMOJI);
    expect($reaction->emoji())->toBeNull();
    expect($reaction->customEmojiId())->toBe('custom123');
    expect($reaction->isEmoji())->toBeFalse();
    expect($reaction->isCustomEmoji())->toBeTrue();
    expect($reaction->isPaidEmoji())->toBeFalse();
});

it('can create paid emoji reaction type from array', function () {
    $data = [
        'type' => ReactionType::TYPE_PAID_EMOJI,
    ];

    $reaction = ReactionType::fromArray($data);

    expect($reaction->type())->toBe(ReactionType::TYPE_PAID_EMOJI);
    expect($reaction->emoji())->toBeNull();
    expect($reaction->customEmojiId())->toBeNull();
    expect($reaction->isEmoji())->toBeFalse();
    expect($reaction->isCustomEmoji())->toBeFalse();
    expect($reaction->isPaidEmoji())->toBeTrue();
});

it('can create reaction type from array with all fields', function () {
    $data = [
        'type' => ReactionType::TYPE_EMOJI,
        'emoji' => '❤️',
        'custom_emoji_id' => null, // Should be ignored for emoji type
    ];

    $reaction = ReactionType::fromArray($data);

    expect($reaction->type())->toBe(ReactionType::TYPE_EMOJI);
    expect($reaction->emoji())->toBe('❤️');
    expect($reaction->customEmojiId())->toBeNull();
});

it('throws exception for missing type', function () {
    $data = [
        'emoji' => '👍',
    ];

    expect(fn () => ReactionType::fromArray($data))
        ->toThrow(ValidationException::class);
});

it('throws exception for invalid type', function () {
    $data = [
        'type' => 'invalid_type',
        'emoji' => '👍',
    ];

    expect(fn () => ReactionType::fromArray($data))
        ->toThrow(ValidationException::class);
});

it('can check reaction type categories', function () {
    $emojiData = [
        'type' => ReactionType::TYPE_EMOJI,
        'emoji' => '👍',
    ];

    $customData = [
        'type' => ReactionType::TYPE_CUSTOM_EMOJI,
        'custom_emoji_id' => 'custom123',
    ];

    $paidData = [
        'type' => ReactionType::TYPE_PAID_EMOJI,
    ];

    $emojiReaction = ReactionType::fromArray($emojiData);
    $customReaction = ReactionType::fromArray($customData);
    $paidReaction = ReactionType::fromArray($paidData);

    // Test emoji reaction
    expect($emojiReaction->isEmoji())->toBeTrue();
    expect($emojiReaction->isCustomEmoji())->toBeFalse();
    expect($emojiReaction->isPaidEmoji())->toBeFalse();

    // Test custom emoji reaction
    expect($customReaction->isEmoji())->toBeFalse();
    expect($customReaction->isCustomEmoji())->toBeTrue();
    expect($customReaction->isPaidEmoji())->toBeFalse();

    // Test paid emoji reaction
    expect($paidReaction->isEmoji())->toBeFalse();
    expect($paidReaction->isCustomEmoji())->toBeFalse();
    expect($paidReaction->isPaidEmoji())->toBeTrue();
});

it('can get display value', function () {
    $emojiData = [
        'type' => ReactionType::TYPE_EMOJI,
        'emoji' => '🔥',
    ];

    $customData = [
        'type' => ReactionType::TYPE_CUSTOM_EMOJI,
        'custom_emoji_id' => 'fire_custom',
    ];

    $paidData = [
        'type' => ReactionType::TYPE_PAID_EMOJI,
    ];

    $emojiReaction = ReactionType::fromArray($emojiData);
    $customReaction = ReactionType::fromArray($customData);
    $paidReaction = ReactionType::fromArray($paidData);

    expect($emojiReaction->getDisplayValue())->toBe('🔥');
    expect($customReaction->getDisplayValue())->toBe('Custom emoji: fire_custom');
    expect($paidReaction->getDisplayValue())->toBe('Paid reaction');
});

it('can convert to array', function () {
    $data = [
        'type' => ReactionType::TYPE_EMOJI,
        'emoji' => '👍',
    ];

    $reaction = ReactionType::fromArray($data);
    $array = $reaction->toArray();

    expect($array)->toHaveKey('type');
    expect($array)->toHaveKey('emoji');
    expect($array['type'])->toBe(ReactionType::TYPE_EMOJI);
    expect($array['emoji'])->toBe('👍');
});

it('filters null values in toArray', function () {
    $data = [
        'type' => ReactionType::TYPE_EMOJI,
        'emoji' => '👍',
    ];

    $reaction = ReactionType::fromArray($data);
    $array = $reaction->toArray();

    expect($array)->not->toHaveKey('custom_emoji_id');
});

it('includes custom emoji id in toArray when present', function () {
    $data = [
        'type' => ReactionType::TYPE_CUSTOM_EMOJI,
        'custom_emoji_id' => 'custom123',
    ];

    $reaction = ReactionType::fromArray($data);
    $array = $reaction->toArray();

    expect($array)->toHaveKey('type');
    expect($array)->toHaveKey('custom_emoji_id');
    expect($array)->not->toHaveKey('emoji');
    expect($array['type'])->toBe(ReactionType::TYPE_CUSTOM_EMOJI);
    expect($array['custom_emoji_id'])->toBe('custom123');
});
