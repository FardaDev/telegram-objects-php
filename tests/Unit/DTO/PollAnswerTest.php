<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: tests/Unit/DTO/PollAnswerTest.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-07
 */

use Telegram\Objects\DTO\Chat;
use Telegram\Objects\DTO\PollAnswer;
use Telegram\Objects\DTO\User;
use Telegram\Objects\Exceptions\ValidationException;

it('can create poll answer from array with minimal fields', function () {
    $pollAnswer = PollAnswer::fromArray([
        'poll_id' => 'poll123',
        'option_ids' => [0, 2],
    ]);

    expect($pollAnswer->pollId())->toBe('poll123');
    expect($pollAnswer->optionIds()->toArray())->toBe([0, 2]);
    expect($pollAnswer->user())->toBeNull();
    expect($pollAnswer->voterChat())->toBeNull();
});

it('can create poll answer from array with all fields', function () {
    $pollAnswer = PollAnswer::fromArray([
        'poll_id' => 'poll456',
        'user' => [
            'id' => 789,
            'is_bot' => false,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'johndoe',
            'language_code' => 'en',
            'is_premium' => true,
        ],
        'voter_chat' => [
            'id' => '123456',
            'type' => 'group',
            'title' => 'Test Group',
        ],
        'option_ids' => [1],
    ]);

    expect($pollAnswer->pollId())->toBe('poll456');
    expect($pollAnswer->optionIds()->toArray())->toBe([1]);
    expect($pollAnswer->user())->toBeInstanceOf(User::class);
    expect($pollAnswer->user()->id())->toBe(789);
    expect($pollAnswer->user()->fullName())->toBe('John Doe');
    expect($pollAnswer->voterChat())->toBeInstanceOf(Chat::class);
    expect($pollAnswer->voterChat()->id())->toBe('123456');
    expect($pollAnswer->voterChat()->title())->toBe('Test Group');
});

it('can convert to array', function () {
    $data = [
        'poll_id' => 'poll789',
        'user' => [
            'id' => 456,
            'is_bot' => false,
            'first_name' => 'Jane',
        ],
        'option_ids' => [0, 1, 2],
    ];

    $pollAnswer = PollAnswer::fromArray($data);
    $result = $pollAnswer->toArray();

    expect($result)->toHaveKey('poll_id', 'poll789');
    expect($result)->toHaveKey('user');
    expect($result['user'])->toHaveKey('id', 456);
    expect($result['user'])->toHaveKey('first_name', 'Jane');
    expect($result['option_ids'])->toBe([0, 1, 2]);
});

it('filters null values in toArray', function () {
    $pollAnswer = PollAnswer::fromArray([
        'poll_id' => 'poll123',
        'option_ids' => [0],
    ]);

    $result = $pollAnswer->toArray();

    expect($result)->toHaveKey('poll_id', 'poll123');
    expect($result['option_ids'])->toBe([0]);
    expect($result)->not->toHaveKey('user');
    expect($result)->not->toHaveKey('voter_chat');
});

it('throws exception for missing required field', function () {
    PollAnswer::fromArray([
        'option_ids' => [0],
    ]);
})->throws(ValidationException::class, "Missing required field 'poll_id'");

it('throws exception for missing option_ids', function () {
    PollAnswer::fromArray([
        'poll_id' => 'poll123',
    ]);
})->throws(ValidationException::class, "Missing required field 'option_ids'");

it('can handle empty poll_id', function () {
    $pollAnswer = PollAnswer::fromArray([
        'poll_id' => '',
        'option_ids' => [0],
    ]);

    expect($pollAnswer->pollId())->toBe('');
});

it('can handle empty option_ids array', function () {
    $pollAnswer = PollAnswer::fromArray([
        'poll_id' => 'poll123',
        'option_ids' => [],
    ]);

    expect($pollAnswer->optionIds())->toHaveCount(0);
    expect($pollAnswer->getSelectedOptionsCount())->toBe(0);
});

it('throws exception for negative option_id', function () {
    PollAnswer::fromArray([
        'poll_id' => 'poll123',
        'option_ids' => [-1],
    ]);
})->throws(\InvalidArgumentException::class, "Option IDs must be non-negative");

it('can check if answer is anonymous', function () {
    $anonymousAnswer = PollAnswer::fromArray([
        'poll_id' => 'poll123',
        'option_ids' => [0],
    ]);

    $identifiedAnswer = PollAnswer::fromArray([
        'poll_id' => 'poll456',
        'user' => [
            'id' => 789,
            'is_bot' => false,
            'first_name' => 'John',
        ],
        'option_ids' => [1],
    ]);

    expect($anonymousAnswer->isAnonymous())->toBeTrue();
    expect($identifiedAnswer->isAnonymous())->toBeFalse();
});

it('can check if answer has user', function () {
    $withoutUser = PollAnswer::fromArray([
        'poll_id' => 'poll123',
        'option_ids' => [0],
    ]);

    $withUser = PollAnswer::fromArray([
        'poll_id' => 'poll456',
        'user' => [
            'id' => 789,
            'is_bot' => false,
            'first_name' => 'John',
        ],
        'option_ids' => [1],
    ]);

    expect($withoutUser->isAnonymous())->toBeTrue();
    expect($withUser->isAnonymous())->toBeFalse();
});

it('can check if answer has voter chat', function () {
    $withoutChat = PollAnswer::fromArray([
        'poll_id' => 'poll123',
        'option_ids' => [0],
    ]);

    $withChat = PollAnswer::fromArray([
        'poll_id' => 'poll456',
        'voter_chat' => [
            'id' => '123456',
            'type' => 'group',
            'title' => 'Test Group',
        ],
        'option_ids' => [1],
    ]);

    expect($withoutChat->isFromChat())->toBeFalse();
    expect($withChat->isFromChat())->toBeTrue();
});

it('can get selected options count', function () {
    $singleOption = PollAnswer::fromArray([
        'poll_id' => 'poll123',
        'option_ids' => [2],
    ]);

    $multipleOptions = PollAnswer::fromArray([
        'poll_id' => 'poll456',
        'option_ids' => [0, 1, 3],
    ]);

    expect($singleOption->getSelectedOptionsCount())->toBe(1);
    expect($multipleOptions->getSelectedOptionsCount())->toBe(3);
});

it('can check if specific option is selected', function () {
    $pollAnswer = PollAnswer::fromArray([
        'poll_id' => 'poll123',
        'option_ids' => [0, 2, 4],
    ]);

    expect($pollAnswer->hasSelectedOption(0))->toBeTrue();
    expect($pollAnswer->hasSelectedOption(1))->toBeFalse();
    expect($pollAnswer->hasSelectedOption(2))->toBeTrue();
    expect($pollAnswer->hasSelectedOption(3))->toBeFalse();
    expect($pollAnswer->hasSelectedOption(4))->toBeTrue();
});
