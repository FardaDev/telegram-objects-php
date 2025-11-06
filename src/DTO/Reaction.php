<?php declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/src/DTO/Reaction.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-07
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Exceptions\ValidationException;
use Telegram\Objects\Support\Collection;
use Telegram\Objects\Support\TelegramDateTime;

/**
 * Represents a change in the list of the chosen reactions for a message.
 * 
 * This object represents a change in the list of the chosen reactions 
 * for a message. For example, when a user adds or removes a reaction.
 */
class Reaction implements ArrayableInterface, SerializableInterface
{
    private int $messageId;
    private Chat $chat;
    private ?Chat $actorChat = null;
    private ?User $from = null;

    /** @var Collection<int, ReactionType> */
    private Collection $oldReaction;

    /** @var Collection<int, ReactionType> */
    private Collection $newReaction;

    private TelegramDateTime $date;

    private function __construct()
    {
        $this->oldReaction = new Collection([]);
        $this->newReaction = new Collection([]);
    }

    /**
     * Create a Reaction instance from an array of data.
     *
     * @param array<string, mixed> $data The reaction data
     * @return self
     * @throws ValidationException If required fields are missing or invalid
     */
    public static function fromArray(array $data): self
    {
        if (!isset($data['message_id'])) {
            throw new ValidationException("Missing required field 'message_id'");
        }

        if (!isset($data['chat']) || !is_array($data['chat'])) {
            throw new ValidationException("Missing or invalid required field 'chat'");
        }

        if (!isset($data['date'])) {
            throw new ValidationException("Missing required field 'date'");
        }

        $reaction = new self();

        $reaction->messageId = $data['message_id'];
        $reaction->chat = Chat::fromArray($data['chat']);
        $reaction->date = TelegramDateTime::fromTimestamp($data['date']);

        if (isset($data['actor_chat']) && is_array($data['actor_chat'])) {
            $reaction->actorChat = Chat::fromArray($data['actor_chat']);
        }

        if (isset($data['user']) && is_array($data['user'])) {
            $reaction->from = User::fromArray($data['user']);
        }

        // Process old reactions
        if (isset($data['old_reaction']) && is_array($data['old_reaction'])) {
            $oldReactions = [];
            foreach ($data['old_reaction'] as $reactionData) {
                if (is_array($reactionData)) {
                    $oldReactions[] = ReactionType::fromArray($reactionData);
                }
            }
            $reaction->oldReaction = new Collection($oldReactions);
        }

        // Process new reactions
        if (isset($data['new_reaction']) && is_array($data['new_reaction'])) {
            $newReactions = [];
            foreach ($data['new_reaction'] as $reactionData) {
                if (is_array($reactionData)) {
                    $newReactions[] = ReactionType::fromArray($reactionData);
                }
            }
            $reaction->newReaction = new Collection($newReactions);
        }

        return $reaction;
    }

    /**
     * Get the message ID.
     */
    public function messageId(): int
    {
        return $this->messageId;
    }

    /**
     * Get the chat where the reaction occurred.
     */
    public function chat(): Chat
    {
        return $this->chat;
    }

    /**
     * Get the actor chat (for anonymous reactions).
     */
    public function actorChat(): ?Chat
    {
        return $this->actorChat;
    }

    /**
     * Get the user who changed the reaction.
     */
    public function from(): ?User
    {
        return $this->from;
    }

    /**
     * Get the old reactions.
     *
     * @return Collection<int, ReactionType>
     */
    public function oldReaction(): Collection
    {
        return $this->oldReaction;
    }

    /**
     * Get the new reactions.
     *
     * @return Collection<int, ReactionType>
     */
    public function newReaction(): Collection
    {
        return $this->newReaction;
    }

    /**
     * Get the date when the reaction was changed.
     */
    public function date(): TelegramDateTime
    {
        return $this->date;
    }

    /**
     * Check if this is an anonymous reaction (from a chat).
     */
    public function isAnonymous(): bool
    {
        return $this->actorChat !== null && $this->from === null;
    }

    /**
     * Check if reactions were added.
     */
    public function hasAddedReactions(): bool
    {
        return $this->newReaction->count() > $this->oldReaction->count();
    }

    /**
     * Check if reactions were removed.
     */
    public function hasRemovedReactions(): bool
    {
        return $this->newReaction->count() < $this->oldReaction->count();
    }

    /**
     * Check if reactions were changed (not just added or removed).
     */
    public function hasChangedReactions(): bool
    {
        return $this->newReaction->count() === $this->oldReaction->count() && 
               $this->newReaction->count() > 0;
    }

    /**
     * Get the count of reactions added.
     */
    public function getAddedReactionsCount(): int
    {
        return max(0, $this->newReaction->count() - $this->oldReaction->count());
    }

    /**
     * Get the count of reactions removed.
     */
    public function getRemovedReactionsCount(): int
    {
        return max(0, $this->oldReaction->count() - $this->newReaction->count());
    }

    /**
     * Get a summary of the reaction change.
     */
    public function getChangeSummary(): string
    {
        $oldCount = $this->oldReaction->count();
        $newCount = $this->newReaction->count();
        
        if ($newCount > $oldCount) {
            $added = $newCount - $oldCount;
            return "Added {$added} reaction(s)";
        }
        
        if ($newCount < $oldCount) {
            $removed = $oldCount - $newCount;
            return "Removed {$removed} reaction(s)";
        }
        
        if ($newCount > 0) {
            return "Changed {$newCount} reaction(s)";
        }
        
        return "No reactions";
    }

    /**
     * Convert the reaction to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'message_id' => $this->messageId,
            'chat' => $this->chat->toArray(),
            'actor_chat' => $this->actorChat?->toArray(),
            'user' => $this->from?->toArray(),
            'old_reaction' => $this->oldReaction->toArray(),
            'new_reaction' => $this->newReaction->toArray(),
            'date' => $this->date->getTimestamp(),
        ], fn ($value) => $value !== null);
    }
}