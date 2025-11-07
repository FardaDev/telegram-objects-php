<?php

declare(strict_types=1);

/**
 * Created for: telegram-objects-php (https://github.com/FardaDev/telegram-objects-php)
 * Purpose: Test suite for TelegramDateTime class - no equivalent exists in Telegraph source
 * Created: 2025-11-06
 */

use Telegram\Objects\Support\TelegramDateTime;

it('can create from timestamp', function () {
    $timestamp = 1699276800; // 2023-11-06 16:00:00 UTC
    $dateTime = TelegramDateTime::fromTimestamp($timestamp);

    expect($dateTime->getTimestamp())->toBe($timestamp);
});

it('can create from DateTime', function () {
    $dateTime = new \DateTimeImmutable('2023-11-06 16:00:00 UTC');
    $telegramDateTime = TelegramDateTime::fromDateTime($dateTime);

    expect($telegramDateTime->getTimestamp())->toBe($dateTime->getTimestamp());
});

it('can create current time', function () {
    $now = TelegramDateTime::now();
    $currentTime = time();

    // Allow 1 second difference for test execution time
    expect(abs($now->getTimestamp() - $currentTime))->toBeLessThanOrEqual(1);
});

it('can format to ISO string', function () {
    $timestamp = 1699276800; // 2023-11-06 13:20:00 UTC
    $dateTime = TelegramDateTime::fromTimestamp($timestamp);

    expect($dateTime->toISOString())->toBe('2023-11-06T13:20:00+00:00');
});

it('can format with custom format', function () {
    $timestamp = 1699276800; // 2023-11-06 13:20:00 UTC
    $dateTime = TelegramDateTime::fromTimestamp($timestamp);

    expect($dateTime->format('Y-m-d H:i:s'))->toBe('2023-11-06 13:20:00');
});

it('can compare dates', function () {
    $date1 = TelegramDateTime::fromTimestamp(1699276800);
    $date2 = TelegramDateTime::fromTimestamp(1699276900); // 100 seconds later
    $date3 = TelegramDateTime::fromTimestamp(1699276800); // same as date1

    expect($date1->isBefore($date2))->toBeTrue();
    expect($date2->isAfter($date1))->toBeTrue();
    expect($date1->equals($date3))->toBeTrue();

    expect($date1->compare($date2))->toBe(-1);
    expect($date2->compare($date1))->toBe(1);
    expect($date1->compare($date3))->toBe(0);
});

it('can convert to string', function () {
    $timestamp = 1699276800;
    $dateTime = TelegramDateTime::fromTimestamp($timestamp);

    expect((string) $dateTime)->toBe('2023-11-06T13:20:00+00:00');
});

it('can get underlying DateTime', function () {
    $timestamp = 1699276800;
    $telegramDateTime = TelegramDateTime::fromTimestamp($timestamp);
    $dateTime = $telegramDateTime->toDateTime();

    expect($dateTime)->toBeInstanceOf(\DateTimeImmutable::class);
    expect($dateTime->getTimestamp())->toBe($timestamp);
});
