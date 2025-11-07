<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: src/DTO/ChatInviteLink.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-07
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Support\TelegramDateTime;
use Telegram\Objects\Support\Validator;

/**
 * Represents a chat invite link.
 *
 * This class contains information about an invite link for a chat,
 * including its properties, creator, and usage statistics.
 */
class ChatInviteLink implements ArrayableInterface, SerializableInterface
{
    private string $inviteLink;
    private User $creator;
    private bool $createsJoinRequest;
    private bool $isPrimary;
    private bool $isRevoked;
    private ?string $name = null;
    private ?TelegramDateTime $expireDate = null;
    private ?int $memberLimit = null;
    private ?int $pendingJoinRequestsCount = null;
    private ?int $subscriptionPeriod = null;
    private ?int $subscriptionPrice = null;

    private function __construct()
    {
    }

    /**
     * Create a ChatInviteLink instance from an array of data.
     *
     * @param array<string, mixed> $data The chat invite link data
     * @return self
     * @throws \Telegram\Objects\Exceptions\ValidationException If required fields are missing or invalid
     */
    public static function fromArray(array $data): self
    {
        $invite = new self();

        Validator::requireField($data, 'invite_link', 'ChatInviteLink');
        Validator::requireField($data, 'creator', 'ChatInviteLink');
        Validator::requireField($data, 'creates_join_request', 'ChatInviteLink');
        Validator::requireField($data, 'is_primary', 'ChatInviteLink');
        Validator::requireField($data, 'is_revoked', 'ChatInviteLink');

        $inviteLink = Validator::getValue($data, 'invite_link', null, 'string');
        $creatorData = Validator::getValue($data, 'creator', null, 'array');
        $createsJoinRequest = Validator::getValue($data, 'creates_join_request', null, 'bool');
        $isPrimary = Validator::getValue($data, 'is_primary', null, 'bool');
        $isRevoked = Validator::getValue($data, 'is_revoked', null, 'bool');

        $invite->inviteLink = $inviteLink;
        $invite->creator = User::fromArray($creatorData);
        $invite->createsJoinRequest = $createsJoinRequest;
        $invite->isPrimary = $isPrimary;
        $invite->isRevoked = $isRevoked;

        $invite->name = Validator::getValue($data, 'name', null, 'string');

        $expireDate = Validator::getValue($data, 'expire_date', null, 'int');
        if ($expireDate !== null) {
            $invite->expireDate = TelegramDateTime::fromTimestamp($expireDate);
        }

        $invite->memberLimit = Validator::getValue($data, 'member_limit', null, 'int');
        $invite->pendingJoinRequestsCount = Validator::getValue($data, 'pending_join_requests_count', null, 'int');
        $invite->subscriptionPeriod = Validator::getValue($data, 'subscription_period', null, 'int');
        $invite->subscriptionPrice = Validator::getValue($data, 'subscription_price', null, 'int');

        return $invite;
    }

    /**
     * Get the invite link URL.
     */
    public function inviteLink(): string
    {
        return $this->inviteLink;
    }

    /**
     * Get the creator of the invite link.
     */
    public function creator(): User
    {
        return $this->creator;
    }

    /**
     * Check if the link creates join requests.
     */
    public function createsJoinRequest(): bool
    {
        return $this->createsJoinRequest;
    }

    /**
     * Check if this is the primary invite link.
     */
    public function isPrimary(): bool
    {
        return $this->isPrimary;
    }

    /**
     * Check if the invite link is revoked.
     */
    public function isRevoked(): bool
    {
        return $this->isRevoked;
    }

    /**
     * Get the name of the invite link.
     */
    public function name(): ?string
    {
        return $this->name;
    }

    /**
     * Get the expiration date of the invite link.
     */
    public function expireDate(): ?TelegramDateTime
    {
        return $this->expireDate;
    }

    /**
     * Get the member limit for the invite link.
     */
    public function memberLimit(): ?int
    {
        return $this->memberLimit;
    }

    /**
     * Get the number of pending join requests.
     */
    public function pendingJoinRequestsCount(): ?int
    {
        return $this->pendingJoinRequestsCount;
    }

    /**
     * Get the subscription period in seconds.
     */
    public function subscriptionPeriod(): ?int
    {
        return $this->subscriptionPeriod;
    }

    /**
     * Get the subscription price in the smallest currency unit.
     */
    public function subscriptionPrice(): ?int
    {
        return $this->subscriptionPrice;
    }

    /**
     * Check if the invite link has expired.
     */
    public function hasExpired(): bool
    {
        if ($this->expireDate === null) {
            return false;
        }

        return $this->expireDate->getTimestamp() < time();
    }

    /**
     * Check if the invite link has a member limit.
     */
    public function hasMemberLimit(): bool
    {
        return $this->memberLimit !== null;
    }

    /**
     * Check if the invite link is for a subscription.
     */
    public function isSubscription(): bool
    {
        return $this->subscriptionPeriod !== null || $this->subscriptionPrice !== null;
    }

    /**
     * Check if the invite link is active (not revoked and not expired).
     */
    public function isActive(): bool
    {
        return ! $this->isRevoked && ! $this->hasExpired();
    }

    /**
     * Get the remaining time until expiration in seconds.
     */
    public function timeUntilExpiration(): ?int
    {
        if ($this->expireDate === null) {
            return null;
        }

        $remaining = $this->expireDate->getTimestamp() - time();

        return (int) max(0, $remaining);
    }

    /**
     * Convert the chat invite link to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'invite_link' => $this->inviteLink,
            'creator' => $this->creator->toArray(),
            'creates_join_request' => $this->createsJoinRequest,
            'is_primary' => $this->isPrimary,
            'is_revoked' => $this->isRevoked,
            'name' => $this->name,
            'expire_date' => $this->expireDate?->getTimestamp(),
            'member_limit' => $this->memberLimit,
            'pending_join_requests_count' => $this->pendingJoinRequestsCount,
            'subscription_period' => $this->subscriptionPeriod,
            'subscription_price' => $this->subscriptionPrice,
        ], fn ($value) => $value !== null);
    }
}
