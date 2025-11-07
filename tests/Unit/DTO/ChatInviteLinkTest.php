<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: tests/Unit/DTO/ChatInviteLinkTest.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-07
 */

use Telegram\Objects\DTO\ChatInviteLink;
use Telegram\Objects\DTO\User;
use Telegram\Objects\Exceptions\ValidationException;
use Telegram\Objects\Support\TelegramDateTime;

it('can create chat invite link from array with minimal fields', function () {
    $data = [
        'invite_link' => 'https://t.me/+abc123',
        'creator' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'Admin',
        ],
        'creates_join_request' => false,
        'is_primary' => true,
        'is_revoked' => false,
    ];

    $link = ChatInviteLink::fromArray($data);

    expect($link->inviteLink())->toBe('https://t.me/+abc123');
    expect($link->creator())->toBeInstanceOf(User::class);
    expect($link->creator()->id())->toBe(123456789);
    expect($link->createsJoinRequest())->toBeFalse();
    expect($link->isPrimary())->toBeTrue();
    expect($link->isRevoked())->toBeFalse();
    expect($link->name())->toBeNull();
    expect($link->expireDate())->toBeNull();
    expect($link->memberLimit())->toBeNull();
    expect($link->pendingJoinRequestsCount())->toBeNull();
    expect($link->subscriptionPeriod())->toBeNull();
    expect($link->subscriptionPrice())->toBeNull();
});

it('can create chat invite link from array with all fields', function () {
    $data = [
        'invite_link' => 'https://t.me/+abc123',
        'creator' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'Admin',
            'username' => 'admin',
        ],
        'creates_join_request' => true,
        'is_primary' => false,
        'is_revoked' => false,
        'name' => 'VIP Members',
        'expire_date' => 1640995200,
        'member_limit' => 100,
        'pending_join_requests_count' => 5,
        'subscription_period' => 2592000, // 30 days
        'subscription_price' => 500, // $5.00
    ];

    $link = ChatInviteLink::fromArray($data);

    expect($link->inviteLink())->toBe('https://t.me/+abc123');
    expect($link->creator()->username())->toBe('admin');
    expect($link->createsJoinRequest())->toBeTrue();
    expect($link->isPrimary())->toBeFalse();
    expect($link->isRevoked())->toBeFalse();
    expect($link->name())->toBe('VIP Members');
    expect($link->expireDate())->toBeInstanceOf(TelegramDateTime::class);
    expect($link->expireDate()->getTimestamp())->toBe(1640995200);
    expect($link->memberLimit())->toBe(100);
    expect($link->pendingJoinRequestsCount())->toBe(5);
    expect($link->subscriptionPeriod())->toBe(2592000);
    expect($link->subscriptionPrice())->toBe(500);
});

it('throws exception for missing invite_link', function () {
    $data = [
        'creator' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'Admin',
        ],
        'creates_join_request' => false,
        'is_primary' => true,
        'is_revoked' => false,
    ];

    expect(fn () => ChatInviteLink::fromArray($data))
        ->toThrow(ValidationException::class);
});

it('throws exception for missing creator', function () {
    $data = [
        'invite_link' => 'https://t.me/+abc123',
        'creates_join_request' => false,
        'is_primary' => true,
        'is_revoked' => false,
    ];

    expect(fn () => ChatInviteLink::fromArray($data))
        ->toThrow(ValidationException::class);
});

it('throws exception for missing creates_join_request', function () {
    $data = [
        'invite_link' => 'https://t.me/+abc123',
        'creator' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'Admin',
        ],
        'is_primary' => true,
        'is_revoked' => false,
    ];

    expect(fn () => ChatInviteLink::fromArray($data))
        ->toThrow(ValidationException::class);
});

it('can check if link has expired', function () {
    $pastDate = time() - 3600; // 1 hour ago
    $futureDate = time() + 3600; // 1 hour from now

    $expiredData = [
        'invite_link' => 'https://t.me/+abc123',
        'creator' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'Admin',
        ],
        'creates_join_request' => false,
        'is_primary' => false,
        'is_revoked' => false,
        'expire_date' => $pastDate,
    ];

    $activeData = [
        'invite_link' => 'https://t.me/+abc456',
        'creator' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'Admin',
        ],
        'creates_join_request' => false,
        'is_primary' => false,
        'is_revoked' => false,
        'expire_date' => $futureDate,
    ];

    $expiredLink = ChatInviteLink::fromArray($expiredData);
    $activeLink = ChatInviteLink::fromArray($activeData);

    expect($expiredLink->hasExpired())->toBeTrue();
    expect($activeLink->hasExpired())->toBeFalse();
});

it('can check if link has member limit', function () {
    $limitedData = [
        'invite_link' => 'https://t.me/+abc123',
        'creator' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'Admin',
        ],
        'creates_join_request' => false,
        'is_primary' => false,
        'is_revoked' => false,
        'member_limit' => 50,
    ];

    $unlimitedData = [
        'invite_link' => 'https://t.me/+abc456',
        'creator' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'Admin',
        ],
        'creates_join_request' => false,
        'is_primary' => false,
        'is_revoked' => false,
    ];

    $limitedLink = ChatInviteLink::fromArray($limitedData);
    $unlimitedLink = ChatInviteLink::fromArray($unlimitedData);

    expect($limitedLink->hasMemberLimit())->toBeTrue();
    expect($unlimitedLink->hasMemberLimit())->toBeFalse();
});

it('can check if link is for subscription', function () {
    $subscriptionData = [
        'invite_link' => 'https://t.me/+abc123',
        'creator' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'Admin',
        ],
        'creates_join_request' => false,
        'is_primary' => false,
        'is_revoked' => false,
        'subscription_period' => 2592000,
        'subscription_price' => 500,
    ];

    $regularData = [
        'invite_link' => 'https://t.me/+abc456',
        'creator' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'Admin',
        ],
        'creates_join_request' => false,
        'is_primary' => false,
        'is_revoked' => false,
    ];

    $subscriptionLink = ChatInviteLink::fromArray($subscriptionData);
    $regularLink = ChatInviteLink::fromArray($regularData);

    expect($subscriptionLink->isSubscription())->toBeTrue();
    expect($regularLink->isSubscription())->toBeFalse();
});

it('can check if link is active', function () {
    $activeData = [
        'invite_link' => 'https://t.me/+abc123',
        'creator' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'Admin',
        ],
        'creates_join_request' => false,
        'is_primary' => false,
        'is_revoked' => false,
    ];

    $revokedData = [
        'invite_link' => 'https://t.me/+abc456',
        'creator' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'Admin',
        ],
        'creates_join_request' => false,
        'is_primary' => false,
        'is_revoked' => true,
    ];

    $activeLink = ChatInviteLink::fromArray($activeData);
    $revokedLink = ChatInviteLink::fromArray($revokedData);

    expect($activeLink->isActive())->toBeTrue();
    expect($revokedLink->isActive())->toBeFalse();
});

it('can get time until expiration', function () {
    $futureDate = time() + 3600; // 1 hour from now

    $data = [
        'invite_link' => 'https://t.me/+abc123',
        'creator' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'Admin',
        ],
        'creates_join_request' => false,
        'is_primary' => false,
        'is_revoked' => false,
        'expire_date' => $futureDate,
    ];

    $link = ChatInviteLink::fromArray($data);
    $timeUntilExpiration = $link->timeUntilExpiration();

    expect($timeUntilExpiration)->toBeGreaterThan(3500); // Should be close to 3600
    expect($timeUntilExpiration)->toBeLessThanOrEqual(3600);
});

it('can convert to array', function () {
    $data = [
        'invite_link' => 'https://t.me/+abc123',
        'creator' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'Admin',
        ],
        'creates_join_request' => false,
        'is_primary' => true,
        'is_revoked' => false,
        'name' => 'Test Link',
    ];

    $link = ChatInviteLink::fromArray($data);
    $array = $link->toArray();

    expect($array)->toHaveKey('invite_link');
    expect($array)->toHaveKey('creator');
    expect($array)->toHaveKey('creates_join_request');
    expect($array)->toHaveKey('is_primary');
    expect($array)->toHaveKey('is_revoked');
    expect($array)->toHaveKey('name');
    expect($array['invite_link'])->toBe('https://t.me/+abc123');
    expect($array['name'])->toBe('Test Link');
});

it('filters null values in toArray', function () {
    $data = [
        'invite_link' => 'https://t.me/+abc123',
        'creator' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'Admin',
        ],
        'creates_join_request' => false,
        'is_primary' => true,
        'is_revoked' => false,
    ];

    $link = ChatInviteLink::fromArray($data);
    $array = $link->toArray();

    expect($array)->not->toHaveKey('name');
    expect($array)->not->toHaveKey('expire_date');
    expect($array)->not->toHaveKey('member_limit');
    expect($array)->not->toHaveKey('pending_join_requests_count');
    expect($array)->not->toHaveKey('subscription_period');
    expect($array)->not->toHaveKey('subscription_price');
});
