<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/src/DTO/Poll.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-06
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Support\Collection;
use Telegram\Objects\Support\Validator;

/**
 * Contains information about a poll.
 *
 * This object contains information about a poll.
 */
class Poll implements ArrayableInterface, SerializableInterface
{
    /**
     * @param string $id Unique poll identifier
     * @param string $question Poll question, 1-300 characters
     * @param Collection<array-key, Entity> $questionEntities Special entities that appear in the question
     * @param Collection<array-key, PollOption> $options List of poll options
     * @param int $totalVoterCount Total number of users that voted in the poll
     * @param bool $isClosed True, if the poll is closed
     * @param bool $isAnonymous True, if the poll is anonymous
     * @param string $type Poll type, currently can be "regular" or "quiz"
     * @param bool $allowsMultipleAnswers True, if the poll allows multiple answers
     * @param int|null $correctOptionId 0-based identifier of the correct answer option. Available only for polls in the quiz mode
     * @param string|null $explanation Text that is shown when a user chooses an incorrect answer or taps on the lamp icon in a quiz-style poll
     * @param Collection<array-key, Entity>|null $explanationEntities Special entities like usernames, URLs, bot commands, etc. that appear in the explanation
     * @param int|null $openPeriod Amount of time in seconds the poll will be active after creation
     * @param int|null $closeDate Point in time (Unix timestamp) when the poll will be automatically closed
     */
    private function __construct(
        private readonly string $id,
        private readonly string $question,
        private readonly Collection $questionEntities,
        private readonly Collection $options,
        private readonly int $totalVoterCount,
        private readonly bool $isClosed,
        private readonly bool $isAnonymous,
        private readonly string $type,
        private readonly bool $allowsMultipleAnswers,
        private readonly ?int $correctOptionId = null,
        private readonly ?string $explanation = null,
        private readonly ?Collection $explanationEntities = null,
        private readonly ?int $openPeriod = null,
        private readonly ?int $closeDate = null,
    ) {
    }

    /**
     * Create a Poll instance from array data.
     *
     * @param array<string, mixed> $data The poll data from Telegram API
     * @return self
     */
    public static function fromArray(array $data): self
    {
        Validator::requireField($data, 'id', 'Poll');
        Validator::requireField($data, 'question', 'Poll');
        Validator::requireField($data, 'options', 'Poll');
        Validator::requireField($data, 'total_voter_count', 'Poll');
        Validator::requireField($data, 'is_closed', 'Poll');
        Validator::requireField($data, 'is_anonymous', 'Poll');
        Validator::requireField($data, 'type', 'Poll');
        Validator::requireField($data, 'allows_multiple_answers', 'Poll');

        $id = Validator::getValue($data, 'id', null, 'string');
        $question = Validator::getValue($data, 'question', null, 'string');
        $optionsArray = Validator::getValue($data, 'options', null, 'array');
        $totalVoterCount = Validator::getValue($data, 'total_voter_count', null, 'int');
        $isClosed = Validator::getValue($data, 'is_closed', null, 'bool');
        $isAnonymous = Validator::getValue($data, 'is_anonymous', null, 'bool');
        $type = Validator::getValue($data, 'type', null, 'string');
        $allowsMultipleAnswers = Validator::getValue($data, 'allows_multiple_answers', null, 'bool');
        $correctOptionId = Validator::getValue($data, 'correct_option_id', null, 'int');
        $explanation = Validator::getValue($data, 'explanation', null, 'string');
        $openPeriod = Validator::getValue($data, 'open_period', null, 'int');
        $closeDate = Validator::getValue($data, 'close_date', null, 'int');

        // Validate question length
        Validator::validateStringLength($question, 'question', 1, 300);

        // Validate total voter count is non-negative
        Validator::validateRange($totalVoterCount, 'total_voter_count', 0);

        // Validate poll type
        $validTypes = ['regular', 'quiz'];
        if (! in_array($type, $validTypes, true)) {
            throw new \InvalidArgumentException("Invalid poll type: {$type}. Must be one of: " . implode(', ', $validTypes));
        }

        // Handle question entities
        $questionEntities = Collection::make([]);
        if (isset($data['question_entities']) && is_array($data['question_entities'])) {
            $entityData = array_map(fn ($entityArray) => Entity::fromArray($entityArray), $data['question_entities']);
            $questionEntities = Collection::make($entityData);
        }

        // Handle poll options
        $optionData = array_map(fn ($optionArray) => PollOption::fromArray($optionArray), $optionsArray);
        $options = Collection::make($optionData);

        // Handle explanation entities
        $explanationEntities = null;
        if (isset($data['explanation_entities']) && is_array($data['explanation_entities'])) {
            $entityData = array_map(fn ($entityArray) => Entity::fromArray($entityArray), $data['explanation_entities']);
            $explanationEntities = Collection::make($entityData);
        }

        return new self(
            id: $id,
            question: $question,
            questionEntities: $questionEntities,
            options: $options,
            totalVoterCount: $totalVoterCount,
            isClosed: $isClosed,
            isAnonymous: $isAnonymous,
            type: $type,
            allowsMultipleAnswers: $allowsMultipleAnswers,
            correctOptionId: $correctOptionId,
            explanation: $explanation,
            explanationEntities: $explanationEntities,
            openPeriod: $openPeriod,
            closeDate: $closeDate,
        );
    }

    /**
     * Get the unique poll identifier.
     *
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * Get the poll question.
     *
     * @return string
     */
    public function question(): string
    {
        return $this->question;
    }

    /**
     * Get the special entities that appear in the question.
     *
     * @return Collection<array-key, Entity>
     */
    public function questionEntities(): Collection
    {
        return $this->questionEntities;
    }

    /**
     * Get the list of poll options.
     *
     * @return Collection<array-key, PollOption>
     */
    public function options(): Collection
    {
        return $this->options;
    }

    /**
     * Get the total number of users that voted in the poll.
     *
     * @return int
     */
    public function totalVoterCount(): int
    {
        return $this->totalVoterCount;
    }

    /**
     * Check if the poll is closed.
     *
     * @return bool
     */
    public function isClosed(): bool
    {
        return $this->isClosed;
    }

    /**
     * Check if the poll is anonymous.
     *
     * @return bool
     */
    public function isAnonymous(): bool
    {
        return $this->isAnonymous;
    }

    /**
     * Get the poll type.
     *
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * Check if the poll allows multiple answers.
     *
     * @return bool
     */
    public function allowsMultipleAnswers(): bool
    {
        return $this->allowsMultipleAnswers;
    }

    /**
     * Get the 0-based identifier of the correct answer option.
     *
     * @return int|null
     */
    public function correctOptionId(): ?int
    {
        return $this->correctOptionId;
    }

    /**
     * Get the text that is shown when a user chooses an incorrect answer.
     *
     * @return string|null
     */
    public function explanation(): ?string
    {
        return $this->explanation;
    }

    /**
     * Get the special entities that appear in the explanation.
     *
     * @return Collection<array-key, Entity>|null
     */
    public function explanationEntities(): ?Collection
    {
        return $this->explanationEntities;
    }

    /**
     * Get the amount of time in seconds the poll will be active after creation.
     *
     * @return int|null
     */
    public function openPeriod(): ?int
    {
        return $this->openPeriod;
    }

    /**
     * Get the point in time when the poll will be automatically closed.
     *
     * @return int|null
     */
    public function closeDate(): ?int
    {
        return $this->closeDate;
    }

    /**
     * Check if this is a quiz poll.
     *
     * @return bool
     */
    public function isQuiz(): bool
    {
        return $this->type === 'quiz';
    }

    /**
     * Check if this is a regular poll.
     *
     * @return bool
     */
    public function isRegular(): bool
    {
        return $this->type === 'regular';
    }

    /**
     * Check if the poll has question entities.
     *
     * @return bool
     */
    public function hasQuestionEntities(): bool
    {
        return $this->questionEntities->isNotEmpty();
    }

    /**
     * Check if the poll has an explanation.
     *
     * @return bool
     */
    public function hasExplanation(): bool
    {
        return $this->explanation !== null;
    }

    /**
     * Get the number of options in the poll.
     *
     * @return int
     */
    public function getOptionsCount(): int
    {
        return $this->options->count();
    }

    /**
     * Get a specific option by its index.
     *
     * @param int $index The 0-based option index
     * @return PollOption|null
     */
    public function getOption(int $index): ?PollOption
    {
        return $this->options->get($index);
    }

    /**
     * Convert the Poll to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'question' => $this->question,
            'question_entities' => $this->questionEntities->isNotEmpty() ? $this->questionEntities->toArray() : null,
            'options' => $this->options->toArray(),
            'total_voter_count' => $this->totalVoterCount,
            'is_closed' => $this->isClosed,
            'is_anonymous' => $this->isAnonymous,
            'type' => $this->type,
            'allows_multiple_answers' => $this->allowsMultipleAnswers,
            'correct_option_id' => $this->correctOptionId,
            'explanation' => $this->explanation,
            'explanation_entities' => $this->explanationEntities?->toArray(),
            'open_period' => $this->openPeriod,
            'close_date' => $this->closeDate,
        ], fn ($value) => $value !== null);
    }
}
