<?php

declare(strict_types=1);

/**
 * Created for: telegram-objects-php (https://github.com/FardaDev/telegram-objects-php)
 * Purpose: Test suite for TelegramException class - no equivalent exists in Telegraph source
 * Created: 2025-11-06
 */

use Telegram\Objects\Exceptions\TelegramException;

it('can create exception for invalid data', function () {
    $exception = TelegramException::invalidData('username', 123, 'string');

    expect($exception->getMessage())->toBe("Invalid data for field 'username': expected string, got int");
    expect($exception)->toBeInstanceOf(TelegramException::class);
});

it('can create exception for missing required field', function () {
    $exception = TelegramException::missingRequiredField('id', 'User DTO');

    expect($exception->getMessage())->toBe("Missing required field 'id' in User DTO");
});

it('can create exception for invalid enum value', function () {
    $exception = TelegramException::invalidEnumValue('invalid', ['option1', 'option2'], 'chat type');

    expect($exception->getMessage())->toBe("Invalid value 'invalid' for chat type. Allowed values: option1, option2");
});

it('extends base Exception class', function () {
    $exception = new TelegramException('Test message');

    expect($exception)->toBeInstanceOf(\Exception::class);
    expect($exception->getMessage())->toBe('Test message');
});
