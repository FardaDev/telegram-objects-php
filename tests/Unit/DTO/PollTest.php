<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/tests/Unit/DTO/PollTest.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-07
 */

use Telegram\Objects\DTO\Poll;
use Telegram\Objects\DTO\PollOption;
use Telegram\Objects\Exceptions\ValidationException;

it('can create poll from array with minimal fields', function () {
    $poll = Poll::fromArray([
        'id' => 'poll123',
        'question' => 'What is your favorite color?',
        'options' => [
            ['text' => 'Red', 'voter_count' => 5],
            ['text' => 'Blue', 'voter_count' => 3],
        ],
        'total_voter_count' => 8,
        'is_closed' => false,
        'is_anonymous' => true,
        'type' => 'regular',
        'allows_multiple_answers' => false,
    ]);

    expect($poll->id())->toBe('poll123');
    expect($poll->question())->toBe('What is your favorite color?');
    expect($poll->options())->toHaveCount(2);
    expect($poll->totalVoterCount())->toBe(8);
    expect($poll->isClosed())->toBeFalse();
    expect($poll->isAnonymous())->toBeTrue();
    expect($poll->type())->toBe('regular');
    expect($poll->allowsMultipleAnswers())->toBeFalse();
});

it('can create poll from array with all fields', function () {
    $poll = Poll::fromArray([
        'id' => 'quiz456',
        'question' => 'What is 2+2?',
        'question_entities' => [
            [
                'type' => 'bold',
                'offset' => 8,
                'length' => 3,
            ],
        ],
        'options' => [
            ['text' => '3', 'voter_count' => 2],
            ['text' => '4', 'voter_count' => 8],
            ['text' => '5', 'voter_count' => 1],
        ],
        'total_voter_count' => 11,
        'is_closed' => true,
        'is_anonymous' => false,
        'type' => 'quiz',
        'allows_multiple_answers' => false,
        'correct_option_id' => 1,
        'explanation' => 'Basic arithmetic: 2+2=4',
        'explanation_entities' => [
            [
                'type' => 'code',
                'offset' => 17,
                'length' => 5,
            ],
        ],
        'open_period' => 600,
        'close_date' => 1640995200,
    ]);

    expect($poll->id())->toBe('quiz456');
    expect($poll->question())->toBe('What is 2+2?');
    expect($poll->questionEntities())->toHaveCount(1);
    expect($poll->options())->toHaveCount(3);
    expect($poll->totalVoterCount())->toBe(11);
    expect($poll->isClosed())->toBeTrue();
    expect($poll->isAnonymous())->toBeFalse();
    expect($poll->type())->toBe('quiz');
    expect($poll->allowsMultipleAnswers())->toBeFalse();
    expect($poll->correctOptionId())->toBe(1);
    expect($poll->explanation())->toBe('Basic arithmetic: 2+2=4');
    expect($poll->explanationEntities())->toHaveCount(1);
    expect($poll->openPeriod())->toBe(600);
    expect($poll->closeDate())->toBe(1640995200);
});

it('can convert to array', function () {
    $data = [
        'id' => 'poll789',
        'question' => 'Choose your preference',
        'options' => [
            ['text' => 'Option A', 'voter_count' => 10],
            ['text' => 'Option B', 'voter_count' => 15],
        ],
        'total_voter_count' => 25,
        'is_closed' => false,
        'is_anonymous' => true,
        'type' => 'regular',
        'allows_multiple_answers' => true,
    ];

    $poll = Poll::fromArray($data);
    $result = $poll->toArray();

    expect($result)->toHaveKey('id', 'poll789');
    expect($result)->toHaveKey('question', 'Choose your preference');
    expect($result)->toHaveKey('options');
    expect($result['options'])->toHaveCount(2);
    expect($result)->toHaveKey('total_voter_count', 25);
    expect($result)->toHaveKey('is_closed', false);
    expect($result)->toHaveKey('is_anonymous', true);
    expect($result)->toHaveKey('type', 'regular');
    expect($result)->toHaveKey('allows_multiple_answers', true);
});

it('filters null values in toArray', function () {
    $poll = Poll::fromArray([
        'id' => 'poll123',
        'question' => 'Simple poll',
        'options' => [
            ['text' => 'Yes', 'voter_count' => 5],
            ['text' => 'No', 'voter_count' => 3],
        ],
        'total_voter_count' => 8,
        'is_closed' => false,
        'is_anonymous' => true,
        'type' => 'regular',
        'allows_multiple_answers' => false,
    ]);

    $result = $poll->toArray();

    expect($result)->not->toHaveKey('question_entities');
    expect($result)->not->toHaveKey('correct_option_id');
    expect($result)->not->toHaveKey('explanation');
    expect($result)->not->toHaveKey('explanation_entities');
    expect($result)->not->toHaveKey('open_period');
    expect($result)->not->toHaveKey('close_date');
});

it('throws exception for missing required field', function () {
    Poll::fromArray([
        'question' => 'Missing ID',
        'options' => [['text' => 'Option', 'voter_count' => 1]],
        'total_voter_count' => 1,
        'is_closed' => false,
        'is_anonymous' => true,
        'type' => 'regular',
        'allows_multiple_answers' => false,
    ]);
})->throws(ValidationException::class, "Missing required field 'id'");

it('throws exception for empty question', function () {
    Poll::fromArray([
        'id' => 'poll123',
        'question' => '',
        'options' => [['text' => 'Option', 'voter_count' => 1]],
        'total_voter_count' => 1,
        'is_closed' => false,
        'is_anonymous' => true,
        'type' => 'regular',
        'allows_multiple_answers' => false,
    ]);
})->throws(ValidationException::class, "Field 'question' has invalid length");

it('can handle empty options array', function () {
    $poll = Poll::fromArray([
        'id' => 'poll123',
        'question' => 'Question',
        'options' => [],
        'total_voter_count' => 0,
        'is_closed' => false,
        'is_anonymous' => true,
        'type' => 'regular',
        'allows_multiple_answers' => false,
    ]);

    expect($poll->options())->toHaveCount(0);
    expect($poll->getOptionsCount())->toBe(0);
});

it('throws exception for invalid poll type', function () {
    Poll::fromArray([
        'id' => 'poll123',
        'question' => 'Question',
        'options' => [['text' => 'Option', 'voter_count' => 1]],
        'total_voter_count' => 1,
        'is_closed' => false,
        'is_anonymous' => true,
        'type' => 'invalid',
        'allows_multiple_answers' => false,
    ]);
})->throws(\InvalidArgumentException::class, "Invalid poll type: invalid");

it('throws exception for negative total voter count', function () {
    Poll::fromArray([
        'id' => 'poll123',
        'question' => 'Question',
        'options' => [['text' => 'Option', 'voter_count' => 1]],
        'total_voter_count' => -1,
        'is_closed' => false,
        'is_anonymous' => true,
        'type' => 'regular',
        'allows_multiple_answers' => false,
    ]);
})->throws(ValidationException::class, "Field 'total_voter_count' value -1 is out of allowed range");

it('can check if poll is quiz', function () {
    $regularPoll = Poll::fromArray([
        'id' => 'poll123',
        'question' => 'Regular poll',
        'options' => [['text' => 'Option', 'voter_count' => 1]],
        'total_voter_count' => 1,
        'is_closed' => false,
        'is_anonymous' => true,
        'type' => 'regular',
        'allows_multiple_answers' => false,
    ]);

    $quizPoll = Poll::fromArray([
        'id' => 'quiz123',
        'question' => 'Quiz poll',
        'options' => [['text' => 'Option', 'voter_count' => 1]],
        'total_voter_count' => 1,
        'is_closed' => false,
        'is_anonymous' => true,
        'type' => 'quiz',
        'allows_multiple_answers' => false,
        'correct_option_id' => 0,
    ]);

    expect($regularPoll->isQuiz())->toBeFalse();
    expect($regularPoll->isRegular())->toBeTrue();
    expect($quizPoll->isQuiz())->toBeTrue();
    expect($quizPoll->isRegular())->toBeFalse();
});

it('can check if poll has question entities', function () {
    $pollWithoutEntities = Poll::fromArray([
        'id' => 'poll123',
        'question' => 'Simple question',
        'options' => [['text' => 'Option', 'voter_count' => 1]],
        'total_voter_count' => 1,
        'is_closed' => false,
        'is_anonymous' => true,
        'type' => 'regular',
        'allows_multiple_answers' => false,
    ]);

    $pollWithEntities = Poll::fromArray([
        'id' => 'poll456',
        'question' => 'Question with entities',
        'question_entities' => [
            ['type' => 'bold', 'offset' => 0, 'length' => 8],
        ],
        'options' => [['text' => 'Option', 'voter_count' => 1]],
        'total_voter_count' => 1,
        'is_closed' => false,
        'is_anonymous' => true,
        'type' => 'regular',
        'allows_multiple_answers' => false,
    ]);

    expect($pollWithoutEntities->hasQuestionEntities())->toBeFalse();
    expect($pollWithEntities->hasQuestionEntities())->toBeTrue();
});

it('can check if poll has explanation', function () {
    $pollWithoutExplanation = Poll::fromArray([
        'id' => 'poll123',
        'question' => 'Simple question',
        'options' => [['text' => 'Option', 'voter_count' => 1]],
        'total_voter_count' => 1,
        'is_closed' => false,
        'is_anonymous' => true,
        'type' => 'regular',
        'allows_multiple_answers' => false,
    ]);

    $pollWithExplanation = Poll::fromArray([
        'id' => 'quiz456',
        'question' => 'Quiz question',
        'options' => [['text' => 'Option', 'voter_count' => 1]],
        'total_voter_count' => 1,
        'is_closed' => false,
        'is_anonymous' => true,
        'type' => 'quiz',
        'allows_multiple_answers' => false,
        'correct_option_id' => 0,
        'explanation' => 'This is the explanation',
    ]);

    expect($pollWithoutExplanation->hasExplanation())->toBeFalse();
    expect($pollWithExplanation->hasExplanation())->toBeTrue();
});

it('can get options count', function () {
    $poll = Poll::fromArray([
        'id' => 'poll123',
        'question' => 'Multiple options',
        'options' => [
            ['text' => 'Option 1', 'voter_count' => 1],
            ['text' => 'Option 2', 'voter_count' => 2],
            ['text' => 'Option 3', 'voter_count' => 3],
        ],
        'total_voter_count' => 6,
        'is_closed' => false,
        'is_anonymous' => true,
        'type' => 'regular',
        'allows_multiple_answers' => false,
    ]);

    expect($poll->getOptionsCount())->toBe(3);
});

it('can get specific option by index', function () {
    $poll = Poll::fromArray([
        'id' => 'poll123',
        'question' => 'Multiple options',
        'options' => [
            ['text' => 'First', 'voter_count' => 1],
            ['text' => 'Second', 'voter_count' => 2],
            ['text' => 'Third', 'voter_count' => 3],
        ],
        'total_voter_count' => 6,
        'is_closed' => false,
        'is_anonymous' => true,
        'type' => 'regular',
        'allows_multiple_answers' => false,
    ]);

    $firstOption = $poll->getOption(0);
    $secondOption = $poll->getOption(1);
    $invalidOption = $poll->getOption(5);

    expect($firstOption)->toBeInstanceOf(PollOption::class);
    expect($firstOption->text())->toBe('First');
    expect($secondOption->text())->toBe('Second');
    expect($invalidOption)->toBeNull();
});
