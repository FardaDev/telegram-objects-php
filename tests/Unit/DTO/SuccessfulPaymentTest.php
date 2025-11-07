<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: tests/Unit/DTO/SuccessfulPaymentTest.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-07
 */

use Telegram\Objects\DTO\OrderInfo;
use Telegram\Objects\DTO\SuccessfulPayment;
use Telegram\Objects\Exceptions\ValidationException;

it('can create successful payment from array with minimal fields', function () {
    $payment = SuccessfulPayment::fromArray([
        'currency' => 'USD',
        'total_amount' => 999,
        'invoice_payload' => 'premium_monthly_123',
        'telegram_payment_charge_id' => 'tg_charge_123',
        'provider_payment_charge_id' => 'stripe_charge_456',
    ]);

    expect($payment->currency())->toBe('USD');
    expect($payment->totalAmount())->toBe(999);
    expect($payment->invoicePayload())->toBe('premium_monthly_123');
    expect($payment->telegramPaymentChargeId())->toBe('tg_charge_123');
    expect($payment->providerPaymentChargeId())->toBe('stripe_charge_456');
    expect($payment->subscriptionExpirationDate())->toBeNull();
    expect($payment->isRecurring())->toBeFalse();
    expect($payment->isFirstRecurring())->toBeFalse();
    expect($payment->shippingOptionId())->toBeNull();
    expect($payment->orderInfo())->toBeNull();
});

it('can create successful payment from array with all fields', function () {
    $payment = SuccessfulPayment::fromArray([
        'currency' => 'EUR',
        'total_amount' => 1500,
        'invoice_payload' => 'subscription_annual_456',
        'telegram_payment_charge_id' => 'tg_charge_789',
        'provider_payment_charge_id' => 'paypal_charge_012',
        'subscription_expiration_date' => 1735689600, // 2025-01-01
        'is_recurring' => true,
        'is_first_recurring' => false,
        'shipping_option_id' => 'express_shipping',
        'order_info' => [
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
        ],
    ]);

    expect($payment->currency())->toBe('EUR');
    expect($payment->totalAmount())->toBe(1500);
    expect($payment->invoicePayload())->toBe('subscription_annual_456');
    expect($payment->subscriptionExpirationDate())->toBe(1735689600);
    expect($payment->isRecurring())->toBeTrue();
    expect($payment->isFirstRecurring())->toBeFalse();
    expect($payment->shippingOptionId())->toBe('express_shipping');
    expect($payment->orderInfo())->toBeInstanceOf(OrderInfo::class);
    expect($payment->orderInfo()->name())->toBe('John Doe');
});

it('can convert to array', function () {
    $data = [
        'currency' => 'GBP',
        'total_amount' => 2000,
        'invoice_payload' => 'test_payload',
        'telegram_payment_charge_id' => 'tg_123',
        'provider_payment_charge_id' => 'provider_456',
        'is_recurring' => true,
        'is_first_recurring' => true,
        'shipping_option_id' => 'standard',
    ];

    $payment = SuccessfulPayment::fromArray($data);
    $result = $payment->toArray();

    expect($result)->toHaveKey('currency', 'GBP');
    expect($result)->toHaveKey('total_amount', 2000);
    expect($result)->toHaveKey('invoice_payload', 'test_payload');
    expect($result)->toHaveKey('telegram_payment_charge_id', 'tg_123');
    expect($result)->toHaveKey('provider_payment_charge_id', 'provider_456');
    expect($result)->toHaveKey('is_recurring', true);
    expect($result)->toHaveKey('is_first_recurring', true);
    expect($result)->toHaveKey('shipping_option_id', 'standard');
});

it('filters null values in toArray', function () {
    $payment = SuccessfulPayment::fromArray([
        'currency' => 'USD',
        'total_amount' => 999,
        'invoice_payload' => 'test_payload',
        'telegram_payment_charge_id' => 'tg_123',
        'provider_payment_charge_id' => 'provider_456',
    ]);

    $result = $payment->toArray();

    expect($result)->toHaveKey('currency', 'USD');
    expect($result)->toHaveKey('is_recurring', false);
    expect($result)->toHaveKey('is_first_recurring', false);
    expect($result)->not->toHaveKey('subscription_expiration_date');
    expect($result)->not->toHaveKey('shipping_option_id');
    expect($result)->not->toHaveKey('order_info');
});

it('throws exception for missing currency', function () {
    SuccessfulPayment::fromArray([
        'total_amount' => 999,
        'invoice_payload' => 'test_payload',
        'telegram_payment_charge_id' => 'tg_123',
        'provider_payment_charge_id' => 'provider_456',
    ]);
})->throws(ValidationException::class, "Missing required field 'currency'");

it('throws exception for missing total_amount', function () {
    SuccessfulPayment::fromArray([
        'currency' => 'USD',
        'invoice_payload' => 'test_payload',
        'telegram_payment_charge_id' => 'tg_123',
        'provider_payment_charge_id' => 'provider_456',
    ]);
})->throws(ValidationException::class, "Missing required field 'total_amount'");

it('throws exception for missing invoice_payload', function () {
    SuccessfulPayment::fromArray([
        'currency' => 'USD',
        'total_amount' => 999,
        'telegram_payment_charge_id' => 'tg_123',
        'provider_payment_charge_id' => 'provider_456',
    ]);
})->throws(ValidationException::class, "Missing required field 'invoice_payload'");

it('throws exception for missing telegram_payment_charge_id', function () {
    SuccessfulPayment::fromArray([
        'currency' => 'USD',
        'total_amount' => 999,
        'invoice_payload' => 'test_payload',
        'provider_payment_charge_id' => 'provider_456',
    ]);
})->throws(ValidationException::class, "Missing required field 'telegram_payment_charge_id'");

it('throws exception for missing provider_payment_charge_id', function () {
    SuccessfulPayment::fromArray([
        'currency' => 'USD',
        'total_amount' => 999,
        'invoice_payload' => 'test_payload',
        'telegram_payment_charge_id' => 'tg_123',
    ]);
})->throws(ValidationException::class, "Missing required field 'provider_payment_charge_id'");

it('throws exception for invalid currency code', function () {
    SuccessfulPayment::fromArray([
        'currency' => 'DOLLAR',
        'total_amount' => 999,
        'invoice_payload' => 'test_payload',
        'telegram_payment_charge_id' => 'tg_123',
        'provider_payment_charge_id' => 'provider_456',
    ]);
})->throws(ValidationException::class, "Field 'currency' has invalid length");

it('throws exception for negative total amount', function () {
    SuccessfulPayment::fromArray([
        'currency' => 'USD',
        'total_amount' => -999,
        'invoice_payload' => 'test_payload',
        'telegram_payment_charge_id' => 'tg_123',
        'provider_payment_charge_id' => 'provider_456',
    ]);
})->throws(ValidationException::class, "Field 'total_amount' value -999 is out of allowed range");

it('can check subscription expiration', function () {
    $withExpiration = SuccessfulPayment::fromArray([
        'currency' => 'USD',
        'total_amount' => 999,
        'invoice_payload' => 'test_payload',
        'telegram_payment_charge_id' => 'tg_123',
        'provider_payment_charge_id' => 'provider_456',
        'subscription_expiration_date' => 1735689600,
    ]);

    $withoutExpiration = SuccessfulPayment::fromArray([
        'currency' => 'USD',
        'total_amount' => 999,
        'invoice_payload' => 'test_payload',
        'telegram_payment_charge_id' => 'tg_123',
        'provider_payment_charge_id' => 'provider_456',
    ]);

    expect($withExpiration->hasSubscriptionExpiration())->toBeTrue();
    expect($withoutExpiration->hasSubscriptionExpiration())->toBeFalse();
});

it('can check shipping option', function () {
    $withShipping = SuccessfulPayment::fromArray([
        'currency' => 'USD',
        'total_amount' => 999,
        'invoice_payload' => 'test_payload',
        'telegram_payment_charge_id' => 'tg_123',
        'provider_payment_charge_id' => 'provider_456',
        'shipping_option_id' => 'express',
    ]);

    $withoutShipping = SuccessfulPayment::fromArray([
        'currency' => 'USD',
        'total_amount' => 999,
        'invoice_payload' => 'test_payload',
        'telegram_payment_charge_id' => 'tg_123',
        'provider_payment_charge_id' => 'provider_456',
    ]);

    expect($withShipping->hasShippingOption())->toBeTrue();
    expect($withoutShipping->hasShippingOption())->toBeFalse();
});

it('can check order info', function () {
    $withOrderInfo = SuccessfulPayment::fromArray([
        'currency' => 'USD',
        'total_amount' => 999,
        'invoice_payload' => 'test_payload',
        'telegram_payment_charge_id' => 'tg_123',
        'provider_payment_charge_id' => 'provider_456',
        'order_info' => [
            'name' => 'John Doe',
        ],
    ]);

    $withoutOrderInfo = SuccessfulPayment::fromArray([
        'currency' => 'USD',
        'total_amount' => 999,
        'invoice_payload' => 'test_payload',
        'telegram_payment_charge_id' => 'tg_123',
        'provider_payment_charge_id' => 'provider_456',
    ]);

    expect($withOrderInfo->hasOrderInfo())->toBeTrue();
    expect($withoutOrderInfo->hasOrderInfo())->toBeFalse();
});

it('can format amount', function () {
    $payment = SuccessfulPayment::fromArray([
        'currency' => 'USD',
        'total_amount' => 1234,
        'invoice_payload' => 'test_payload',
        'telegram_payment_charge_id' => 'tg_123',
        'provider_payment_charge_id' => 'provider_456',
    ]);

    expect($payment->formatAmount())->toBe('12.34');
    expect($payment->formatAmount(0))->toBe('1,234');
});
