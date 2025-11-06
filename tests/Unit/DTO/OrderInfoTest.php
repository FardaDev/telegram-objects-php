<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/tests/Unit/DTO/OrderInfoTest.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-07
 */

use Telegram\Objects\DTO\OrderInfo;
use Telegram\Objects\DTO\ShippingAddress;
use Telegram\Objects\Exceptions\ValidationException;

it('can create order info from array with minimal fields', function () {
    $orderInfo = OrderInfo::fromArray([]);

    expect($orderInfo->name())->toBeNull();
    expect($orderInfo->phoneNumber())->toBeNull();
    expect($orderInfo->email())->toBeNull();
    expect($orderInfo->shippingAddress())->toBeNull();
});

it('can create order info from array with all fields', function () {
    $orderInfo = OrderInfo::fromArray([
        'name' => 'John Doe',
        'phone_number' => '+1234567890',
        'email' => 'john.doe@example.com',
        'shipping_address' => [
            'country_code' => 'US',
            'state' => 'California',
            'city' => 'San Francisco',
            'street_line1' => '123 Main Street',
            'street_line2' => 'Apt 4B',
            'post_code' => '94102',
        ],
    ]);

    expect($orderInfo->name())->toBe('John Doe');
    expect($orderInfo->phoneNumber())->toBe('+1234567890');
    expect($orderInfo->email())->toBe('john.doe@example.com');
    expect($orderInfo->shippingAddress())->toBeInstanceOf(ShippingAddress::class);
    expect($orderInfo->shippingAddress()->countryCode())->toBe('US');
});

it('can convert to array', function () {
    $data = [
        'name' => 'Jane Smith',
        'phone_number' => '+9876543210',
        'email' => 'jane.smith@example.com',
        'shipping_address' => [
            'country_code' => 'GB',
            'state' => 'England',
            'city' => 'London',
            'street_line1' => '10 Downing Street',
            'street_line2' => '',
            'post_code' => 'SW1A 2AA',
        ],
    ];

    $orderInfo = OrderInfo::fromArray($data);
    $result = $orderInfo->toArray();

    expect($result)->toHaveKey('name', 'Jane Smith');
    expect($result)->toHaveKey('phone_number', '+9876543210');
    expect($result)->toHaveKey('email', 'jane.smith@example.com');
    expect($result)->toHaveKey('shipping_address');
    expect($result['shipping_address'])->toHaveKey('country_code', 'GB');
});

it('filters null values in toArray', function () {
    $orderInfo = OrderInfo::fromArray([
        'name' => 'John Doe',
    ]);

    $result = $orderInfo->toArray();

    expect($result)->toHaveKey('name', 'John Doe');
    expect($result)->not->toHaveKey('phone_number');
    expect($result)->not->toHaveKey('email');
    expect($result)->not->toHaveKey('shipping_address');
});

it('throws exception for invalid email format', function () {
    OrderInfo::fromArray([
        'name' => 'John Doe',
        'email' => 'invalid-email',
    ]);
})->throws(ValidationException::class, "Invalid email format");

it('can handle empty email', function () {
    $orderInfo = OrderInfo::fromArray([
        'name' => 'John Doe',
        'email' => '',
    ]);

    expect($orderInfo->email())->toBe('');
});

it('can check if order has name', function () {
    $withName = OrderInfo::fromArray(['name' => 'John Doe']);
    $withoutName = OrderInfo::fromArray([]);

    expect($withName->hasName())->toBeTrue();
    expect($withoutName->hasName())->toBeFalse();
});

it('can check if order has phone number', function () {
    $withPhone = OrderInfo::fromArray(['phone_number' => '+1234567890']);
    $withoutPhone = OrderInfo::fromArray([]);

    expect($withPhone->hasPhoneNumber())->toBeTrue();
    expect($withoutPhone->hasPhoneNumber())->toBeFalse();
});

it('can check if order has email', function () {
    $withEmail = OrderInfo::fromArray(['email' => 'test@example.com']);
    $withoutEmail = OrderInfo::fromArray([]);

    expect($withEmail->hasEmail())->toBeTrue();
    expect($withoutEmail->hasEmail())->toBeFalse();
});

it('can check if order has shipping address', function () {
    $withAddress = OrderInfo::fromArray([
        'shipping_address' => [
            'country_code' => 'US',
            'state' => 'California',
            'city' => 'San Francisco',
            'street_line1' => '123 Main Street',
            'street_line2' => 'Apt 4B',
            'post_code' => '94102',
        ],
    ]);
    $withoutAddress = OrderInfo::fromArray([]);

    expect($withAddress->hasShippingAddress())->toBeTrue();
    expect($withoutAddress->hasShippingAddress())->toBeFalse();
});
