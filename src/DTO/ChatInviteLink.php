<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/src/DTO/ChatInviteLink.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-07
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Exceptions\ValidationException;
use Telegram\Objects\Support\TelegramDateTime;

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
     * @throws ValidationException If required fields are missing or invalid
     */
    public static function fromArray(array $data): self
    {
        if (! isset($data['invite_link'])) {
            throw new ValidationException("Missing required field 'invite_link'");
        }

        if (! isset($data['creator']) || ! is_array($data['creator'])) {
            throw new ValidationException("Missing or invalid required field 'creator'");
        }

        if (! isset($data['creates_join_request'])) {
            throw new ValidationException("Missing required field 'creates_join_request'");
        }

        if (! isset($data['is_primary'])) {
            throw new ValidationException("Missing required field 'is_primary'");
        }

        if (! isset($data['is_revoked'])) {
            throw new ValidationException("Missing required field 'is_revoked'");
        }

        $invite = new self();

        $invite->inviteLink = $data['invite_link'];
        $invite->creator = User::fromArray($data['creator']);
        $invite->createsJoinRequest = $data['creates_join_request'];
        $invite->isPrimary = $data['is_primary'];
        $invite->isRevoked = $data['is_revoked'];

        if (isset($data['name'])) {
            $invite->name = $data['name'];
        }

        if (isset($data['expire_date'])) {
            $invite->expireDate = TelegramDateTime::fromTimestamp($data['expire_date']);
        }

        if (isset($data['member_limit'])) {
            $invite->memberLimit = $data['member_limit'];
        }

        if (isset($data['pending_join_requests_count'])) {
            $invite->pendingJoinRequestsCount = $data['pending_join_requests_count'];
        }

        if (isset($data['subscription_period'])) {
            $invite->subscriptionPeriod = $data['subscription_period'];
        }

        if (isset($data['subscription_price'])) {
            $invite->subscriptionPrice = $data['subscription_price'];
        }

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
