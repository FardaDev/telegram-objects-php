<?php

declare(strict_types=1);

/**
 * Created for: telegram-objects-php (https://github.com/FardaDev/telegram-objects-php)
 * Purpose: Test suite for ValidationException class - no equivalent exists in Telegraph source
 * Created: 2025-11-06
 */

use Telegram\Objects\Exceptions\TelegramException;
use Telegram\Objects\Exceptions\ValidationException;

it('can create exception for invalid array data', function () {
    $exception = ValidationException::invalidArrayData('Invalid format', 'username');

    expect($exception->getMessage())->toBe("Validation failed for field 'username': Invalid format");
    expect($exception)->toBeInstanceOf(TelegramException::class);
});

it('can create exception for invalid type', function () {
    $exception = ValidationException::invalidType('age', 'int', 'not a number');

    expect($exception->getMessage())->toBe("Field 'age' must be of type int, string given");
});

it('can create exception for value out of range', function () {
    $exception = ValidationException::valueOutOfRange('score', 150, 0, 100);

    expect($exception->getMessage())->toBe("Field 'score' value 150 is out of allowed range (allowed range: 0 to 100)");
});

it('can create exception for value below minimum', function () {
    $exception = ValidationException::valueOutOfRange('age', -5, 0);

    expect($exception->getMessage())->toBe("Field 'age' value -5 is out of allowed range (minimum: 0)");
});

it('can create exception for value above maximum', function () {
    $exception = ValidationException::valueOutOfRange('percentage', 150, null, 100);

    expect($exception->getMessage())->toBe("Field 'percentage' value 150 is out of allowed range (maximum: 100)");
});

it('can create exception for invalid string length', function () {
    $exception = ValidationException::invalidStringLength('title', 5, 10, 50);

    expect($exception->getMessage())->toBe("Field 'title' has invalid length of 5 characters (allowed length: 10 to 50 characters)");
});

it('can create exception for string too short', function () {
    $exception = ValidationException::invalidStringLength('password', 3, 8);

    expect($exception->getMessage())->toBe("Field 'password' has invalid length of 3 characters (minimum length: 8 characters)");
});

it('can create exception for string too long', function () {
    $exception = ValidationException::invalidStringLength('description', 1000, null, 500);

    expect($exception->getMessage())->toBe("Field 'description' has invalid length of 1000 characters (maximum length: 500 characters)");
});
