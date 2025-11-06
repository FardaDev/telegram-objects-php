<?php

declare(strict_types=1);

use Telegram\Objects\Exceptions\ValidationException;
use Telegram\Objects\Support\Validator;

it('can validate required fields exist', function () {
    $data = ['name' => 'John', 'age' => 30];

    expect(fn () => Validator::requireField($data, 'name', 'User'))
        ->not->toThrow(ValidationException::class);
});

it('throws exception for missing required field', function () {
    $data = ['name' => 'John'];

    expect(fn () => Validator::requireField($data, 'age', 'User'))
        ->toThrow(ValidationException::class, "Missing required field 'age'");
});

it('can validate types correctly', function () {
    expect(fn () => Validator::validateType('hello', 'string', 'name'))
        ->not->toThrow(ValidationException::class);

    expect(fn () => Validator::validateType(42, 'int', 'age'))
        ->not->toThrow(ValidationException::class);

    expect(fn () => Validator::validateType(true, 'bool', 'active'))
        ->not->toThrow(ValidationException::class);
});

it('throws exception for invalid types', function () {
    expect(fn () => Validator::validateType(123, 'string', 'name'))
        ->toThrow(ValidationException::class, "Field 'name' must be of type string, int given");
});

it('can validate string length', function () {
    expect(fn () => Validator::validateStringLength('hello', 'name', 3, 10))
        ->not->toThrow(ValidationException::class);
});

it('throws exception for string too short', function () {
    expect(fn () => Validator::validateStringLength('hi', 'name', 5, 10))
        ->toThrow(ValidationException::class);
});

it('throws exception for string too long', function () {
    expect(fn () => Validator::validateStringLength('this is a very long string', 'name', 5, 10))
        ->toThrow(ValidationException::class);
});

it('can validate numeric ranges', function () {
    expect(fn () => Validator::validateRange(50, 'score', 0, 100))
        ->not->toThrow(ValidationException::class);
});

it('throws exception for value below minimum', function () {
    expect(fn () => Validator::validateRange(-10, 'score', 0, 100))
        ->toThrow(ValidationException::class);
});

it('throws exception for value above maximum', function () {
    expect(fn () => Validator::validateRange(150, 'score', 0, 100))
        ->toThrow(ValidationException::class);
});

it('can validate enum values', function () {
    $allowed = ['red', 'green', 'blue'];

    expect(fn () => Validator::validateEnum('red', $allowed, 'color'))
        ->not->toThrow(ValidationException::class);
});

it('throws exception for invalid enum value', function () {
    $allowed = ['red', 'green', 'blue'];

    expect(fn () => Validator::validateEnum('yellow', $allowed, 'color'))
        ->toThrow(ValidationException::class);
});

it('can validate URLs', function () {
    expect(fn () => Validator::validateUrl('https://example.com', 'website'))
        ->not->toThrow(ValidationException::class);
});

it('throws exception for invalid URLs', function () {
    expect(fn () => Validator::validateUrl('not-a-url', 'website'))
        ->toThrow(ValidationException::class);
});

it('can get values from array with defaults', function () {
    $data = ['name' => 'John', 'age' => 30];

    expect(Validator::getValue($data, 'name'))->toBe('John');
    expect(Validator::getValue($data, 'email', 'default@example.com'))->toBe('default@example.com');
});

it('can get values with type validation', function () {
    $data = ['name' => 'John', 'age' => 30];

    expect(Validator::getValue($data, 'name', null, 'string'))->toBe('John');
    expect(Validator::getValue($data, 'age', null, 'int'))->toBe(30);
});

it('throws exception when getting value with wrong type', function () {
    $data = ['age' => 'thirty'];

    expect(fn () => Validator::getValue($data, 'age', null, 'int'))
        ->toThrow(ValidationException::class);
});
