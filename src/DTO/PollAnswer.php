<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/src/DTO/PollAnswer.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-06
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Support\Collection;
use Telegram\Objects\Support\Validator;

/**
 * Represents an answer of a user in a non-anonymous poll.
 *
 * This object represents an answer of a user in a non-anonymous poll.
 */
class PollAnswer implements ArrayableInterface, SerializableInterface
{
    /**
     * @param string $pollId Unique poll identifier
     * @param Chat|null $voterChat The chat that changed the answer to the poll, if the voter is anonymous
     * @param User|null $user The user that changed the answer to the poll, if the voter isn't anonymous
     * @param Collection<array-key, int> $optionIds 0-based identifiers of chosen answer options
     */
    private function __construct(
        private readonly string $pollId,
        private readonly ?Chat $voterChat,
        private readonly ?User $user,
        private readonly Collection $optionIds,
    ) {
    }

    /**
     * Create a PollAnswer instance from array data.
     *
     * @param array<string, mixed> $data The poll answer data from Telegram API
     * @return self
     */
    public static function fromArray(array $data): self
    {
        Validator::requireField($data, 'poll_id', 'PollAnswer');
        Validator::requireField($data, 'option_ids', 'PollAnswer');

        $pollId = Validator::getValue($data, 'poll_id', null, 'string');
        $optionIdsArray = Validator::getValue($data, 'option_ids', null, 'array');

        // Validate option IDs are integers
        foreach ($optionIdsArray as $optionId) {
            if (! is_int($optionId)) {
                throw new \InvalidArgumentException('All option IDs must be integers');
            }
            if ($optionId < 0) {
                throw new \InvalidArgumentException('Option IDs must be non-negative');
            }
        }

        $optionIds = new Collection($optionIdsArray);

        $voterChat = null;
        if (isset($data['voter_chat']) && is_array($data['voter_chat'])) {
            $voterChat = Chat::fromArray($data['voter_chat']);
        }

        $user = null;
        if (isset($data['user']) && is_array($data['user'])) {
            $user = User::fromArray($data['user']);
        }

        return new self(
            pollId: $pollId,
            voterChat: $voterChat,
            user: $user,
            optionIds: $optionIds,
        );
    }

    /**
     * Get the unique poll identifier.
     *
     * @return string
     */
    public function pollId(): string
    {
        return $this->pollId;
    }

    /**
     * Get the chat that changed the answer to the poll.
     *
     * @return Chat|null
     */
    public function voterChat(): ?Chat
    {
        return $this->voterChat;
    }

    /**
     * Get the user that changed the answer to the poll.
     *
     * @return User|null
     */
    public function user(): ?User
    {
        return $this->user;
    }

    /**
     * Get the 0-based identifiers of chosen answer options.
     *
     * @return Collection<array-key, int>
     */
    public function optionIds(): Collection
    {
        return $this->optionIds;
    }

    /**
     * Check if the voter is anonymous.
     *
     * @return bool
     */
    public function isAnonymous(): bool
    {
        return $this->user === null;
    }

    /**
     * Check if the voter is from a chat.
     *
     * @return bool
     */
    public function isFromChat(): bool
    {
        return $this->voterChat !== null;
    }

    /**
     * Get the number of selected options.
     *
     * @return int
     */
    public function getSelectedOptionsCount(): int
    {
        return $this->optionIds->count();
    }

    /**
     * Check if a specific option was selected.
     *
     * @param int $optionId The 0-based option identifier
     * @return bool
     */
    public function hasSelectedOption(int $optionId): bool
    {
        return in_array($optionId, $this->optionIds->all(), true);
    }

    /**
     * Convert the PollAnswer to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'poll_id' => $this->pollId,
            'voter_chat' => $this->voterChat?->toArray(),
            'user' => $this->user?->toArray(),
            'option_ids' => $this->optionIds->toArray(),
        ], fn ($value) => $value !== null);
    }
}
