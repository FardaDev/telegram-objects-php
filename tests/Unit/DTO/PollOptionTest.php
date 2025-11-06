<?php

declare(strict_types=1);

use Telegram\Objects\DTO\Entity;
use Telegram\Objects\DTO\PollOption;
use Telegram\Objects\Exceptions\ValidationException;

it('can create poll option from array with minimal fields', function () {
    $pollOption = PollOption::fromArray([
        'text' => 'Option A',
        'voter_count' => 5,
    ]);

    expect($pollOption->text())->toBe('Option A');
    expect($pollOption->voterCount())->toBe(5);
    expect($pollOption->textEntities()->isEmpty())->toBeTrue();
    expect($pollOption->hasTextEntities())->toBeFalse();
    expect($pollOption->getVotePercentage(10))->toBe(50.0);
    expect($pollOption->getVotePercentage(0))->toBe(0.0);
});

it('can create poll option from array with all fields', function () {
    $pollOption = PollOption::fromArray([
        'text' => 'Option A with #hashtag',
        'voter_count' => 15,
        'text_entities' => [
            [
                'type' => 'hashtag',
                'offset' => 13,
                'length' => 8,
            ],
        ],
    ]);

    expect($pollOption->text())->toBe('Option A with #hashtag');
    expect($pollOption->voterCount())->toBe(15);
    expect($pollOption->textEntities()->count())->toBe(1);
    expect($pollOption->hasTextEntities())->toBeTrue();
    expect($pollOption->textEntities()->first())->toBeInstanceOf(Entity::class);
    expect($pollOption->getVotePercentage(30))->toBe(50.0);
});

it('can convert to array', function () {
    $data = [
        'text' => 'Option A',
        'voter_count' => 5,
        'text_entities' => [
            [
                'type' => 'hashtag',
                'offset' => 0,
                'length' => 8,
            ],
        ],
    ];

    $pollOption = PollOption::fromArray($data);
    $result = $pollOption->toArray();

    expect($result)->toHaveKey('text', 'Option A');
    expect($result)->toHaveKey('voter_count', 5);
    expect($result)->toHaveKey('text_entities');
});

it('filters null values in toArray', function () {
    $pollOption = PollOption::fromArray([
        'text' => 'Option A',
        'voter_count' => 5,
    ]);

    $result = $pollOption->toArray();

    expect($result)->not->toHaveKey('text_entities');
});

it('throws exception for missing text', function () {
    PollOption::fromArray([
        'voter_count' => 5,
    ]);
})->throws(ValidationException::class, "Missing required field 'text'");

it('throws exception for missing voter_count', function () {
    PollOption::fromArray([
        'text' => 'Option A',
    ]);
})->throws(ValidationException::class, "Missing required field 'voter_count'");

it('throws exception for text too short', function () {
    PollOption::fromArray([
        'text' => '',
        'voter_count' => 5,
    ]);
})->throws(ValidationException::class, 'minimum length: 1');

it('throws exception for text too long', function () {
    PollOption::fromArray([
        'text' => str_repeat('a', 101),
        'voter_count' => 5,
    ]);
})->throws(ValidationException::class, 'maximum length: 100');

it('throws exception for negative voter count', function () {
    PollOption::fromArray([
        'text' => 'Option A',
        'voter_count' => -1,
    ]);
})->throws(ValidationException::class, 'minimum: 0');