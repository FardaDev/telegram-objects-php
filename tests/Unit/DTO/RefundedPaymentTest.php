<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/tests/Unit/DTO/RefundedPaymentTest.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-07
 */

use Telegram\Objects\DTO\RefundedPayment;
use Telegram\Objects\Exceptions\ValidationException;

it('can create refunded payment from array with minimal fields', function () {
    $payment = RefundedPayment::fromArray([
        'currency' => 'USD',
        'total_amount' => 999,
        'invoice_payload' => 'refund_test_123',
        'telegram_payment_charge_id' => 'tg_charge_123',
    ]);

    expect($payment->currency())->toBe('USD');
    expect($payment->totalAmount())->toBe(999);
    expect($payment->invoicePayload())->toBe('refund_test_123');
    expect($payment->telegramPaymentChargeId())->toBe('tg_charge_123');
    expect($payment->providerPaymentChargeId())->toBeNull();
});

it('can create refunded payment from array with all fields', function () {
    $payment = RefundedPayment::fromArray([
        'currency' => 'EUR',
        'total_amount' => 1500,
        'invoice_payload' => 'refund_annual_456',
        'telegram_payment_charge_id' => 'tg_charge_789',
        'provider_payment_charge_id' => 'stripe_refund_012',
    ]);

    expect($payment->currency())->toBe('EUR');
    expect($payment->totalAmount())->toBe(1500);
    expect($payment->invoicePayload())->toBe('refund_annual_456');
    expect($payment->telegramPaymentChargeId())->toBe('tg_charge_789');
    expect($payment->providerPaymentChargeId())->toBe('stripe_refund_012');
});

it('can convert to array', function () {
    $data = [
        'currency' => 'GBP',
        'total_amount' => 2000,
        'invoice_payload' => 'test_refund_payload',
        'telegram_payment_charge_id' => 'tg_refund_123',
        'provider_payment_charge_id' => 'provider_refund_456',
    ];

    $payment = RefundedPayment::fromArray($data);
    $result = $payment->toArray();

    expect($result)->toBe($data);
});

it('filters null values in toArray', function () {
    $payment = RefundedPayment::fromArray([
        'currency' => 'USD',
        'total_amount' => 999,
        'invoice_payload' => 'test_payload',
        'telegram_payment_charge_id' => 'tg_123',
    ]);

    $result = $payment->toArray();

    expect($result)->toHaveKey('currency', 'USD');
    expect($result)->toHaveKey('total_amount', 999);
    expect($result)->toHaveKey('invoice_payload', 'test_payload');
    expect($result)->toHaveKey('telegram_payment_charge_id', 'tg_123');
    expect($result)->not->toHaveKey('provider_payment_charge_id');
});

it('throws exception for missing currency', function () {
    RefundedPayment::fromArray([
        'total_amount' => 999,
        'invoice_payload' => 'test_payload',
        'telegram_payment_charge_id' => 'tg_123',
    ]);
})->throws(ValidationException::class, "Missing required field 'currency'");

it('throws exception for missing total_amount', function () {
    RefundedPayment::fromArray([
        'currency' => 'USD',
        'invoice_payload' => 'test_payload',
        'telegram_payment_charge_id' => 'tg_123',
    ]);
})->throws(ValidationException::class, "Missing required field 'total_amount'");

it('throws exception for missing invoice_payload', function () {
    RefundedPayment::fromArray([
        'currency' => 'USD',
        'total_amount' => 999,
        'telegram_payment_charge_id' => 'tg_123',
    ]);
})->throws(ValidationException::class, "Missing required field 'invoice_payload'");

it('throws exception for missing telegram_payment_charge_id', function () {
    RefundedPayment::fromArray([
        'currency' => 'USD',
        'total_amount' => 999,
        'invoice_payload' => 'test_payload',
    ]);
})->throws(ValidationException::class, "Missing required field 'telegram_payment_charge_id'");

it('throws exception for invalid currency code', function () {
    RefundedPayment::fromArray([
        'currency' => 'DOLLAR',
        'total_amount' => 999,
        'invoice_payload' => 'test_payload',
        'telegram_payment_charge_id' => 'tg_123',
    ]);
})->throws(ValidationException::class, "Field 'currency' has invalid length");

it('throws exception for negative total amount', function () {
    RefundedPayment::fromArray([
        'currency' => 'USD',
        'total_amount' => -999,
        'invoice_payload' => 'test_payload',
        'telegram_payment_charge_id' => 'tg_123',
    ]);
})->throws(ValidationException::class, "Field 'total_amount' value -999 is out of allowed range");

it('can check provider payment charge id', function () {
    $withProvider = RefundedPayment::fromArray([
        'currency' => 'USD',
        'total_amount' => 999,
        'invoice_payload' => 'test_payload',
        'telegram_payment_charge_id' => 'tg_123',
        'provider_payment_charge_id' => 'provider_456',
    ]);

    $withoutProvider = RefundedPayment::fromArray([
        'currency' => 'USD',
        'total_amount' => 999,
        'invoice_payload' => 'test_payload',
        'telegram_payment_charge_id' => 'tg_123',
    ]);

    expect($withProvider->hasProviderPaymentChargeId())->toBeTrue();
    expect($withoutProvider->hasProviderPaymentChargeId())->toBeFalse();
});

it('can format amount', function () {
    $payment = RefundedPayment::fromArray([
        'currency' => 'USD',
        'total_amount' => 1234,
        'invoice_payload' => 'test_payload',
        'telegram_payment_charge_id' => 'tg_123',
    ]);

    expect($payment->formatAmount())->toBe('12.34');
    expect($payment->formatAmount(0))->toBe('1,234');
});

it('can handle zero refund amount', function () {
    $payment = RefundedPayment::fromArray([
        'currency' => 'USD',
        'total_amount' => 0,
        'invoice_payload' => 'test_payload',
        'telegram_payment_charge_id' => 'tg_123',
    ]);

    expect($payment->totalAmount())->toBe(0);
    expect($payment->formatAmount())->toBe('0.00');
});
