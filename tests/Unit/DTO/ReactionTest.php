<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/tests/Unit/DTO/ReactionTest.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-07
 */

use Telegram\Objects\DTO\Chat;
use Telegram\Objects\DTO\Reaction;
use Telegram\Objects\DTO\ReactionType;
use Telegram\Objects\DTO\User;
use Telegram\Objects\Exceptions\ValidationException;
use Telegram\Objects\Support\Collection;
use Telegram\Objects\Support\TelegramDateTime;

it('can create reaction from array with minimal fields', function () {
    $data = [
        'message_id' => 12345,
        'chat' => [
            'id' => '-1001234567890',
            'type' => 'supergroup',
            'title' => 'Test Group',
        ],
        'date' => 1640995200,
    ];

    $reaction = Reaction::fromArray($data);

    expect($reaction->messageId())->toBe(12345);
    expect($reaction->chat())->toBeInstanceOf(Chat::class);
    expect($reaction->chat()->id())->toBe('-1001234567890');
    expect($reaction->date())->toBeInstanceOf(TelegramDateTime::class);
    expect($reaction->date()->getTimestamp())->toBe(1640995200);
    expect($reaction->actorChat())->toBeNull();
    expect($reaction->from())->toBeNull();
    expect($reaction->oldReaction())->toBeInstanceOf(Collection::class);
    expect($reaction->oldReaction()->count())->toBe(0);
    expect($reaction->newReaction())->toBeInstanceOf(Collection::class);
    expect($reaction->newReaction()->count())->toBe(0);
});

it('can create reaction from array with all fields', function () {
    $data = [
        'message_id' => 12345,
        'chat' => [
            'id' => '-1001234567890',
            'type' => 'supergroup',
            'title' => 'Test Group',
        ],
        'date' => 1640995200,
        'actor_chat' => [
            'id' => '-1009876543210',
            'type' => 'channel',
            'title' => 'Actor Channel',
        ],
        'user' => [
            'id' => 987654321,
            'is_bot' => false,
            'first_name' => 'John',
            'username' => 'johndoe',
        ],
        'old_reaction' => [
            [
                'type' => ReactionType::TYPE_EMOJI,
                'emoji' => '👍',
            ],
            [
                'type' => ReactionType::TYPE_EMOJI,
                'emoji' => '❤️',
            ],
        ],
        'new_reaction' => [
            [
                'type' => ReactionType::TYPE_EMOJI,
                'emoji' => '👍',
            ],
            [
                'type' => ReactionType::TYPE_EMOJI,
                'emoji' => '🔥',
            ],
            [
                'type' => ReactionType::TYPE_CUSTOM_EMOJI,
                'custom_emoji_id' => 'custom123',
            ],
        ],
    ];

    $reaction = Reaction::fromArray($data);

    expect($reaction->messageId())->toBe(12345);
    expect($reaction->actorChat())->toBeInstanceOf(Chat::class);
    expect($reaction->actorChat()->id())->toBe('-1009876543210');
    expect($reaction->from())->toBeInstanceOf(User::class);
    expect($reaction->from()->username())->toBe('johndoe');
    expect($reaction->oldReaction()->count())->toBe(2);
    expect($reaction->newReaction()->count())->toBe(3);

    // Check old reactions
    $oldReactions = $reaction->oldReaction();
    expect($oldReactions->first())->toBeInstanceOf(ReactionType::class);
    expect($oldReactions->first()->emoji())->toBe('👍');

    // Check new reactions
    $newReactions = $reaction->newReaction();
    expect($newReactions->first())->toBeInstanceOf(ReactionType::class);
    expect($newReactions->first()->emoji())->toBe('👍');
});

it('throws exception for missing message_id', function () {
    $data = [
        'chat' => [
            'id' => '-1001234567890',
            'type' => 'supergroup',
            'title' => 'Test Group',
        ],
        'date' => 1640995200,
    ];

    expect(fn () => Reaction::fromArray($data))
        ->toThrow(ValidationException::class);
});

it('throws exception for missing chat', function () {
    $data = [
        'message_id' => 12345,
        'date' => 1640995200,
    ];

    expect(fn () => Reaction::fromArray($data))
        ->toThrow(ValidationException::class);
});

it('throws exception for missing date', function () {
    $data = [
        'message_id' => 12345,
        'chat' => [
            'id' => '-1001234567890',
            'type' => 'supergroup',
            'title' => 'Test Group',
        ],
    ];

    expect(fn () => Reaction::fromArray($data))
        ->toThrow(ValidationException::class);
});

it('can check if reaction is anonymous', function () {
    $anonymousData = [
        'message_id' => 12345,
        'chat' => [
            'id' => '-1001234567890',
            'type' => 'supergroup',
            'title' => 'Test Group',
        ],
        'date' => 1640995200,
        'actor_chat' => [
            'id' => '-1009876543210',
            'type' => 'channel',
            'title' => 'Actor Channel',
        ],
    ];

    $userReactionData = [
        'message_id' => 12345,
        'chat' => [
            'id' => '-1001234567890',
            'type' => 'supergroup',
            'title' => 'Test Group',
        ],
        'date' => 1640995200,
        'user' => [
            'id' => 987654321,
            'is_bot' => false,
            'first_name' => 'John',
        ],
    ];

    $anonymousReaction = Reaction::fromArray($anonymousData);
    $userReaction = Reaction::fromArray($userReactionData);

    expect($anonymousReaction->isAnonymous())->toBeTrue();
    expect($userReaction->isAnonymous())->toBeFalse();
});

it('can check if reactions were added', function () {
    $data = [
        'message_id' => 12345,
        'chat' => [
            'id' => '-1001234567890',
            'type' => 'supergroup',
            'title' => 'Test Group',
        ],
        'date' => 1640995200,
        'old_reaction' => [
            [
                'type' => ReactionType::TYPE_EMOJI,
                'emoji' => '👍',
            ],
        ],
        'new_reaction' => [
            [
                'type' => ReactionType::TYPE_EMOJI,
                'emoji' => '👍',
            ],
            [
                'type' => ReactionType::TYPE_EMOJI,
                'emoji' => '❤️',
            ],
        ],
    ];

    $reaction = Reaction::fromArray($data);

    expect($reaction->hasAddedReactions())->toBeTrue();
    expect($reaction->hasRemovedReactions())->toBeFalse();
    expect($reaction->getAddedReactionsCount())->toBe(1);
    expect($reaction->getRemovedReactionsCount())->toBe(0);
});

it('can check if reactions were removed', function () {
    $data = [
        'message_id' => 12345,
        'chat' => [
            'id' => '-1001234567890',
            'type' => 'supergroup',
            'title' => 'Test Group',
        ],
        'date' => 1640995200,
        'old_reaction' => [
            [
                'type' => ReactionType::TYPE_EMOJI,
                'emoji' => '👍',
            ],
            [
                'type' => ReactionType::TYPE_EMOJI,
                'emoji' => '❤️',
            ],
        ],
        'new_reaction' => [
            [
                'type' => ReactionType::TYPE_EMOJI,
                'emoji' => '👍',
            ],
        ],
    ];

    $reaction = Reaction::fromArray($data);

    expect($reaction->hasAddedReactions())->toBeFalse();
    expect($reaction->hasRemovedReactions())->toBeTrue();
    expect($reaction->getAddedReactionsCount())->toBe(0);
    expect($reaction->getRemovedReactionsCount())->toBe(1);
});

it('can get change summary', function () {
    $data = [
        'message_id' => 12345,
        'chat' => [
            'id' => '-1001234567890',
            'type' => 'supergroup',
            'title' => 'Test Group',
        ],
        'date' => 1640995200,
        'old_reaction' => [
            [
                'type' => ReactionType::TYPE_EMOJI,
                'emoji' => '👍',
            ],
        ],
        'new_reaction' => [
            [
                'type' => ReactionType::TYPE_EMOJI,
                'emoji' => '❤️',
            ],
        ],
    ];

    $reaction = Reaction::fromArray($data);
    $summary = $reaction->getChangeSummary();

    expect($summary)->toBeString();
    expect($summary)->toContain('reaction');
});

it('can convert to array', function () {
    $data = [
        'message_id' => 12345,
        'chat' => [
            'id' => '-1001234567890',
            'type' => 'supergroup',
            'title' => 'Test Group',
        ],
        'date' => 1640995200,
        'old_reaction' => [
            [
                'type' => ReactionType::TYPE_EMOJI,
                'emoji' => '👍',
            ],
        ],
        'new_reaction' => [
            [
                'type' => ReactionType::TYPE_EMOJI,
                'emoji' => '❤️',
            ],
        ],
    ];

    $reaction = Reaction::fromArray($data);
    $array = $reaction->toArray();

    expect($array)->toHaveKey('message_id');
    expect($array)->toHaveKey('chat');
    expect($array)->toHaveKey('date');
    expect($array)->toHaveKey('old_reaction');
    expect($array)->toHaveKey('new_reaction');
    expect($array['message_id'])->toBe(12345);
    expect($array['date'])->toBe(1640995200);
});

it('filters null values in toArray', function () {
    $data = [
        'message_id' => 12345,
        'chat' => [
            'id' => '-1001234567890',
            'type' => 'supergroup',
            'title' => 'Test Group',
        ],
        'date' => 1640995200,
    ];

    $reaction = Reaction::fromArray($data);
    $array = $reaction->toArray();

    expect($array)->not->toHaveKey('actor_chat');
    expect($array)->not->toHaveKey('user');
});
