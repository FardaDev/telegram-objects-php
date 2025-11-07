<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: tests/Unit/DTO/PreCheckoutQueryTest.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-07
 */

use Telegram\Objects\DTO\OrderInfo;
use Telegram\Objects\DTO\PreCheckoutQuery;
use Telegram\Objects\DTO\User;
use Telegram\Objects\Exceptions\ValidationException;

it('can create pre checkout query from array with minimal fields', function () {
    $query = PreCheckoutQuery::fromArray([
        'id' => 'query_123',
        'from' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'John',
        ],
        'currency' => 'USD',
        'total_amount' => 999,
        'invoice_payload' => 'checkout_test_123',
    ]);

    expect($query->id())->toBe('query_123');
    expect($query->from())->toBeInstanceOf(User::class);
    expect($query->from()->id())->toBe(123456789);
    expect($query->currency())->toBe('USD');
    expect($query->totalAmount())->toBe(999);
    expect($query->invoicePayload())->toBe('checkout_test_123');
    expect($query->shippingOptionId())->toBeNull();
    expect($query->orderInfo())->toBeNull();
});

it('can create pre checkout query from array with all fields', function () {
    $query = PreCheckoutQuery::fromArray([
        'id' => 'query_456',
        'from' => [
            'id' => 987654321,
            'is_bot' => false,
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'username' => 'janedoe',
        ],
        'currency' => 'EUR',
        'total_amount' => 1500,
        'invoice_payload' => 'premium_subscription_456',
        'shipping_option_id' => 'express_shipping',
        'order_info' => [
            'name' => 'Jane Doe',
            'phone_number' => '+1234567890',
            'email' => 'jane.doe@example.com',
            'shipping_address' => [
                'country_code' => 'US',
                'state' => 'California',
                'city' => 'San Francisco',
                'street_line1' => '123 Main Street',
                'street_line2' => 'Apt 4B',
                'post_code' => '94102',
            ],
        ],
    ]);

    expect($query->id())->toBe('query_456');
    expect($query->from()->fullName())->toBe('Jane Doe');
    expect($query->currency())->toBe('EUR');
    expect($query->totalAmount())->toBe(1500);
    expect($query->invoicePayload())->toBe('premium_subscription_456');
    expect($query->shippingOptionId())->toBe('express_shipping');
    expect($query->orderInfo())->toBeInstanceOf(OrderInfo::class);
    expect($query->orderInfo()->name())->toBe('Jane Doe');
});

it('can convert to array', function () {
    $data = [
        'id' => 'query_789',
        'from' => [
            'id' => 555666777,
            'is_bot' => false,
            'first_name' => 'Bob',
        ],
        'currency' => 'GBP',
        'total_amount' => 2000,
        'invoice_payload' => 'test_checkout_payload',
        'shipping_option_id' => 'standard_shipping',
    ];

    $query = PreCheckoutQuery::fromArray($data);
    $result = $query->toArray();

    expect($result)->toHaveKey('id', 'query_789');
    expect($result)->toHaveKey('from');
    expect($result['from'])->toHaveKey('id', 555666777);
    expect($result)->toHaveKey('currency', 'GBP');
    expect($result)->toHaveKey('total_amount', 2000);
    expect($result)->toHaveKey('invoice_payload', 'test_checkout_payload');
    expect($result)->toHaveKey('shipping_option_id', 'standard_shipping');
});

it('filters null values in toArray', function () {
    $query = PreCheckoutQuery::fromArray([
        'id' => 'query_123',
        'from' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'John',
        ],
        'currency' => 'USD',
        'total_amount' => 999,
        'invoice_payload' => 'test_payload',
    ]);

    $result = $query->toArray();

    expect($result)->toHaveKey('id', 'query_123');
    expect($result)->toHaveKey('from');
    expect($result)->toHaveKey('currency', 'USD');
    expect($result)->toHaveKey('total_amount', 999);
    expect($result)->toHaveKey('invoice_payload', 'test_payload');
    expect($result)->not->toHaveKey('shipping_option_id');
    expect($result)->not->toHaveKey('order_info');
});

it('throws exception for missing id', function () {
    PreCheckoutQuery::fromArray([
        'from' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'John',
        ],
        'currency' => 'USD',
        'total_amount' => 999,
        'invoice_payload' => 'test_payload',
    ]);
})->throws(ValidationException::class, "Missing required field 'id'");

it('throws exception for missing from', function () {
    PreCheckoutQuery::fromArray([
        'id' => 'query_123',
        'currency' => 'USD',
        'total_amount' => 999,
        'invoice_payload' => 'test_payload',
    ]);
})->throws(ValidationException::class, "Missing required field 'from'");

it('throws exception for missing currency', function () {
    PreCheckoutQuery::fromArray([
        'id' => 'query_123',
        'from' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'John',
        ],
        'total_amount' => 999,
        'invoice_payload' => 'test_payload',
    ]);
})->throws(ValidationException::class, "Missing required field 'currency'");

it('throws exception for missing total_amount', function () {
    PreCheckoutQuery::fromArray([
        'id' => 'query_123',
        'from' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'John',
        ],
        'currency' => 'USD',
        'invoice_payload' => 'test_payload',
    ]);
})->throws(ValidationException::class, "Missing required field 'total_amount'");

it('throws exception for missing invoice_payload', function () {
    PreCheckoutQuery::fromArray([
        'id' => 'query_123',
        'from' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'John',
        ],
        'currency' => 'USD',
        'total_amount' => 999,
    ]);
})->throws(ValidationException::class, "Missing required field 'invoice_payload'");

it('throws exception for invalid currency code', function () {
    PreCheckoutQuery::fromArray([
        'id' => 'query_123',
        'from' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'John',
        ],
        'currency' => 'DOLLAR',
        'total_amount' => 999,
        'invoice_payload' => 'test_payload',
    ]);
})->throws(ValidationException::class, "Field 'currency' has invalid length");

it('throws exception for negative total amount', function () {
    PreCheckoutQuery::fromArray([
        'id' => 'query_123',
        'from' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'John',
        ],
        'currency' => 'USD',
        'total_amount' => -999,
        'invoice_payload' => 'test_payload',
    ]);
})->throws(ValidationException::class, "Field 'total_amount' value -999 is out of allowed range");

it('can check shipping option', function () {
    $withShipping = PreCheckoutQuery::fromArray([
        'id' => 'query_123',
        'from' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'John',
        ],
        'currency' => 'USD',
        'total_amount' => 999,
        'invoice_payload' => 'test_payload',
        'shipping_option_id' => 'express',
    ]);

    $withoutShipping = PreCheckoutQuery::fromArray([
        'id' => 'query_456',
        'from' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'John',
        ],
        'currency' => 'USD',
        'total_amount' => 999,
        'invoice_payload' => 'test_payload',
    ]);

    expect($withShipping->hasShippingOption())->toBeTrue();
    expect($withoutShipping->hasShippingOption())->toBeFalse();
});

it('can check order info', function () {
    $withOrderInfo = PreCheckoutQuery::fromArray([
        'id' => 'query_123',
        'from' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'John',
        ],
        'currency' => 'USD',
        'total_amount' => 999,
        'invoice_payload' => 'test_payload',
        'order_info' => [
            'name' => 'John Doe',
        ],
    ]);

    $withoutOrderInfo = PreCheckoutQuery::fromArray([
        'id' => 'query_456',
        'from' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'John',
        ],
        'currency' => 'USD',
        'total_amount' => 999,
        'invoice_payload' => 'test_payload',
    ]);

    expect($withOrderInfo->hasOrderInfo())->toBeTrue();
    expect($withoutOrderInfo->hasOrderInfo())->toBeFalse();
});

it('can format amount', function () {
    $query = PreCheckoutQuery::fromArray([
        'id' => 'query_123',
        'from' => [
            'id' => 123456789,
            'is_bot' => false,
            'first_name' => 'John',
        ],
        'currency' => 'USD',
        'total_amount' => 1234,
        'invoice_payload' => 'test_payload',
    ]);

    expect($query->formatAmount())->toBe('12.34');
    expect($query->formatAmount(0))->toBe('1,234');
});
