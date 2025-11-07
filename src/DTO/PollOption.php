<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: src/DTO/PollOption.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-06
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Support\Collection;
use Telegram\Objects\Support\Validator;

/**
 * Contains information about one answer option in a poll.
 *
 * This object contains information about one answer option in a poll.
 */
class PollOption implements ArrayableInterface, SerializableInterface
{
    /**
     * @param string $text Option text, 1-100 characters
     * @param Collection<array-key, Entity> $textEntities Special entities that appear in the option text
     * @param int $voterCount Number of users that voted for this option
     */
    private function __construct(
        private readonly string $text,
        private readonly Collection $textEntities,
        private readonly int $voterCount,
    ) {
    }

    /**
     * Create a PollOption instance from array data.
     *
     * @param array<string, mixed> $data The poll option data from Telegram API
     * @return self
     */
    public static function fromArray(array $data): self
    {
        Validator::requireField($data, 'text', 'PollOption');
        Validator::requireField($data, 'voter_count', 'PollOption');

        $text = Validator::getValue($data, 'text', null, 'string');
        $voterCount = Validator::getValue($data, 'voter_count', null, 'int');

        // Validate text length
        Validator::validateStringLength($text, 'text', 1, 100);

        // Validate voter count is non-negative
        Validator::validateRange($voterCount, 'voter_count', 0);

        // Handle text entities
        $textEntities = Collection::make([]);
        if (isset($data['text_entities']) && is_array($data['text_entities'])) {
            $entityData = array_map(fn ($entityArray) => Entity::fromArray($entityArray), $data['text_entities']);
            $textEntities = Collection::make($entityData);
        }

        return new self(
            text: $text,
            textEntities: $textEntities,
            voterCount: $voterCount,
        );
    }

    /**
     * Get the option text.
     *
     * @return string
     */
    public function text(): string
    {
        return $this->text;
    }

    /**
     * Get the special entities that appear in the option text.
     *
     * @return Collection<array-key, Entity>
     */
    public function textEntities(): Collection
    {
        return $this->textEntities;
    }

    /**
     * Get the number of users that voted for this option.
     *
     * @return int
     */
    public function voterCount(): int
    {
        return $this->voterCount;
    }

    /**
     * Check if this option has text entities.
     *
     * @return bool
     */
    public function hasTextEntities(): bool
    {
        return $this->textEntities->isNotEmpty();
    }

    /**
     * Get the percentage of votes for this option.
     *
     * @param int $totalVotes Total number of votes in the poll
     * @return float
     */
    public function getVotePercentage(int $totalVotes): float
    {
        if ($totalVotes === 0) {
            return 0.0;
        }

        return ($this->voterCount / $totalVotes) * 100;
    }

    /**
     * Convert the PollOption to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'text' => $this->text,
            'text_entities' => $this->textEntities->isNotEmpty() ? $this->textEntities->toArray() : null,
            'voter_count' => $this->voterCount,
        ], fn ($value) => $value !== null);
    }
}
