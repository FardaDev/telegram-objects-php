<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: tests/Unit/DTO/ShippingAddressTest.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-07
 */

use Telegram\Objects\DTO\ShippingAddress;
use Telegram\Objects\Exceptions\ValidationException;

it('can create shipping address from array with all fields', function () {
    $shippingAddress = ShippingAddress::fromArray([
        'country_code' => 'US',
        'state' => 'California',
        'city' => 'San Francisco',
        'street_line1' => '123 Main Street',
        'street_line2' => 'Apt 4B',
        'post_code' => '94102',
    ]);

    expect($shippingAddress->countryCode())->toBe('US');
    expect($shippingAddress->state())->toBe('California');
    expect($shippingAddress->city())->toBe('San Francisco');
    expect($shippingAddress->streetLine1())->toBe('123 Main Street');
    expect($shippingAddress->streetLine2())->toBe('Apt 4B');
    expect($shippingAddress->postCode())->toBe('94102');
});

it('can convert to array', function () {
    $data = [
        'country_code' => 'GB',
        'state' => 'England',
        'city' => 'London',
        'street_line1' => '10 Downing Street',
        'street_line2' => '',
        'post_code' => 'SW1A 2AA',
    ];

    $shippingAddress = ShippingAddress::fromArray($data);
    $result = $shippingAddress->toArray();

    expect($result)->toBe($data);
});

it('throws exception for missing country_code', function () {
    ShippingAddress::fromArray([
        'state' => 'California',
        'city' => 'San Francisco',
        'street_line1' => '123 Main Street',
        'street_line2' => 'Apt 4B',
        'post_code' => '94102',
    ]);
})->throws(ValidationException::class, "Missing required field 'country_code'");

it('throws exception for missing state', function () {
    ShippingAddress::fromArray([
        'country_code' => 'US',
        'city' => 'San Francisco',
        'street_line1' => '123 Main Street',
        'street_line2' => 'Apt 4B',
        'post_code' => '94102',
    ]);
})->throws(ValidationException::class, "Missing required field 'state'");

it('throws exception for missing city', function () {
    ShippingAddress::fromArray([
        'country_code' => 'US',
        'state' => 'California',
        'street_line1' => '123 Main Street',
        'street_line2' => 'Apt 4B',
        'post_code' => '94102',
    ]);
})->throws(ValidationException::class, "Missing required field 'city'");

it('throws exception for missing street_line1', function () {
    ShippingAddress::fromArray([
        'country_code' => 'US',
        'state' => 'California',
        'city' => 'San Francisco',
        'street_line2' => 'Apt 4B',
        'post_code' => '94102',
    ]);
})->throws(ValidationException::class, "Missing required field 'street_line1'");

it('throws exception for missing street_line2', function () {
    ShippingAddress::fromArray([
        'country_code' => 'US',
        'state' => 'California',
        'city' => 'San Francisco',
        'street_line1' => '123 Main Street',
        'post_code' => '94102',
    ]);
})->throws(ValidationException::class, "Missing required field 'street_line2'");

it('throws exception for missing post_code', function () {
    ShippingAddress::fromArray([
        'country_code' => 'US',
        'state' => 'California',
        'city' => 'San Francisco',
        'street_line1' => '123 Main Street',
        'street_line2' => 'Apt 4B',
    ]);
})->throws(ValidationException::class, "Missing required field 'post_code'");

it('throws exception for invalid country code length', function () {
    ShippingAddress::fromArray([
        'country_code' => 'USA', // Should be 2 characters
        'state' => 'California',
        'city' => 'San Francisco',
        'street_line1' => '123 Main Street',
        'street_line2' => 'Apt 4B',
        'post_code' => '94102',
    ]);
})->throws(ValidationException::class, "Field 'country_code' has invalid length");

it('can format address', function () {
    $shippingAddress = ShippingAddress::fromArray([
        'country_code' => 'US',
        'state' => 'California',
        'city' => 'San Francisco',
        'street_line1' => '123 Main Street',
        'street_line2' => 'Apt 4B',
        'post_code' => '94102',
    ]);

    $formatted = $shippingAddress->formatAddress();
    expect($formatted)->toBe('123 Main Street, Apt 4B, San Francisco, California, 94102, US');
});

it('can format address with empty street_line2', function () {
    $shippingAddress = ShippingAddress::fromArray([
        'country_code' => 'US',
        'state' => 'California',
        'city' => 'San Francisco',
        'street_line1' => '123 Main Street',
        'street_line2' => '',
        'post_code' => '94102',
    ]);

    $formatted = $shippingAddress->formatAddress();
    expect($formatted)->toBe('123 Main Street, San Francisco, California, 94102, US');
});
