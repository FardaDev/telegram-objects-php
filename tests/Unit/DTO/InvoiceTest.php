<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/tests/Unit/DTO/InvoiceTest.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-07
 */

use Telegram\Objects\DTO\Invoice;
use Telegram\Objects\Exceptions\ValidationException;

it('can create invoice from array with all fields', function () {
    $invoice = Invoice::fromArray([
        'title' => 'Premium Subscription',
        'description' => 'Monthly premium subscription with advanced features',
        'start_parameter' => 'premium_monthly',
        'currency' => 'USD',
        'total_amount' => 999, // $9.99 in cents
    ]);

    expect($invoice->title())->toBe('Premium Subscription');
    expect($invoice->description())->toBe('Monthly premium subscription with advanced features');
    expect($invoice->startParameter())->toBe('premium_monthly');
    expect($invoice->currency())->toBe('USD');
    expect($invoice->totalAmount())->toBe(999);
});

it('can convert to array', function () {
    $data = [
        'title' => 'Basic Plan',
        'description' => 'Basic subscription plan',
        'start_parameter' => 'basic_plan',
        'currency' => 'EUR',
        'total_amount' => 500,
    ];

    $invoice = Invoice::fromArray($data);
    $result = $invoice->toArray();

    expect($result)->toBe($data);
});

it('throws exception for missing title', function () {
    Invoice::fromArray([
        'description' => 'Test description',
        'start_parameter' => 'test',
        'currency' => 'USD',
        'total_amount' => 100,
    ]);
})->throws(ValidationException::class, "Missing required field 'title'");

it('throws exception for missing description', function () {
    Invoice::fromArray([
        'title' => 'Test Title',
        'start_parameter' => 'test',
        'currency' => 'USD',
        'total_amount' => 100,
    ]);
})->throws(ValidationException::class, "Missing required field 'description'");

it('throws exception for missing start_parameter', function () {
    Invoice::fromArray([
        'title' => 'Test Title',
        'description' => 'Test description',
        'currency' => 'USD',
        'total_amount' => 100,
    ]);
})->throws(ValidationException::class, "Missing required field 'start_parameter'");

it('throws exception for missing currency', function () {
    Invoice::fromArray([
        'title' => 'Test Title',
        'description' => 'Test description',
        'start_parameter' => 'test',
        'total_amount' => 100,
    ]);
})->throws(ValidationException::class, "Missing required field 'currency'");

it('throws exception for missing total_amount', function () {
    Invoice::fromArray([
        'title' => 'Test Title',
        'description' => 'Test description',
        'start_parameter' => 'test',
        'currency' => 'USD',
    ]);
})->throws(ValidationException::class, "Missing required field 'total_amount'");

it('throws exception for invalid currency code length', function () {
    Invoice::fromArray([
        'title' => 'Test Title',
        'description' => 'Test description',
        'start_parameter' => 'test',
        'currency' => 'DOLLAR', // Should be 3 characters
        'total_amount' => 100,
    ]);
})->throws(ValidationException::class, "Field 'currency' has invalid length");

it('throws exception for negative total amount', function () {
    Invoice::fromArray([
        'title' => 'Test Title',
        'description' => 'Test description',
        'start_parameter' => 'test',
        'currency' => 'USD',
        'total_amount' => -100,
    ]);
})->throws(ValidationException::class, "Field 'total_amount' value -100 is out of allowed range");

it('can format amount with default decimal places', function () {
    $invoice = Invoice::fromArray([
        'title' => 'Test Product',
        'description' => 'Test description',
        'start_parameter' => 'test',
        'currency' => 'USD',
        'total_amount' => 1234, // $12.34
    ]);

    expect($invoice->formatAmount())->toBe('12.34');
});

it('can format amount with custom decimal places', function () {
    $invoice = Invoice::fromArray([
        'title' => 'Test Product',
        'description' => 'Test description',
        'start_parameter' => 'test',
        'currency' => 'JPY',
        'total_amount' => 1000, // ¥1000 (no decimal places for JPY)
    ]);

    expect($invoice->formatAmount(0))->toBe('1,000');
});

it('can handle zero amount', function () {
    $invoice = Invoice::fromArray([
        'title' => 'Free Product',
        'description' => 'Free product description',
        'start_parameter' => 'free',
        'currency' => 'USD',
        'total_amount' => 0,
    ]);

    expect($invoice->totalAmount())->toBe(0);
    expect($invoice->formatAmount())->toBe('0.00');
});
