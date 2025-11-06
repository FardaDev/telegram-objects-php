<?php

declare(strict_types=1);

use Telegram\Objects\DTO\User;
use Telegram\Objects\Exceptions\ValidationException;

it('can create user from array with all fields', function () {
    $data = [
        'id' => 123456789,
        'is_bot' => false,
        'first_name' => 'John',
        'last_name' => 'Doe',
        'username' => 'johndoe',
        'language_code' => 'en',
        'is_premium' => true,
    ];

    $user = User::fromArray($data);

    expect($user->id())->toBe(123456789);
    expect($user->isBot())->toBeFalse();
    expect($user->firstName())->toBe('John');
    expect($user->lastName())->toBe('Doe');
    expect($user->username())->toBe('johndoe');
    expect($user->languageCode())->toBe('en');
    expect($user->isPremium())->toBeTrue();
});

it('can create user from array with minimal fields', function () {
    $data = [
        'id' => 123456789,
        'first_name' => 'John',
    ];

    $user = User::fromArray($data);

    expect($user->id())->toBe(123456789);
    expect($user->isBot())->toBeFalse();
    expect($user->firstName())->toBe('John');
    expect($user->lastName())->toBeNull();
    expect($user->username())->toBeNull();
    expect($user->languageCode())->toBeNull();
    expect($user->isPremium())->toBeFalse();
});

it('can create bot user', function () {
    $data = [
        'id' => 987654321,
        'is_bot' => true,
        'first_name' => 'TestBot',
        'username' => 'test_bot',
    ];

    $user = User::fromArray($data);

    expect($user->id())->toBe(987654321);
    expect($user->isBot())->toBeTrue();
    expect($user->firstName())->toBe('TestBot');
    expect($user->username())->toBe('test_bot');
});

it('throws exception for missing id', function () {
    $data = [
        'first_name' => 'John',
    ];

    expect(fn () => User::fromArray($data))
        ->toThrow(ValidationException::class, "Missing required field 'id'");
});

it('throws exception for missing first_name', function () {
    $data = [
        'id' => 123456789,
    ];

    expect(fn () => User::fromArray($data))
        ->toThrow(ValidationException::class, "Missing required field 'first_name'");
});

it('can get full name', function () {
    $user1 = User::fromArray([
        'id' => 1,
        'first_name' => 'John',
        'last_name' => 'Doe',
    ]);

    $user2 = User::fromArray([
        'id' => 2,
        'first_name' => 'Jane',
    ]);

    expect($user1->fullName())->toBe('John Doe');
    expect($user2->fullName())->toBe('Jane');
});

it('can convert to array', function () {
    $data = [
        'id' => 123456789,
        'is_bot' => false,
        'first_name' => 'John',
        'last_name' => 'Doe',
        'username' => 'johndoe',
        'language_code' => 'en',
        'is_premium' => true,
    ];

    $user = User::fromArray($data);
    $result = $user->toArray();

    expect($result)->toBe($data);
});

it('filters null values in toArray', function () {
    $data = [
        'id' => 123456789,
        'first_name' => 'John',
    ];

    $user = User::fromArray($data);
    $result = $user->toArray();

    expect($result)->toBe([
        'id' => 123456789,
        'is_bot' => false,
        'first_name' => 'John',
        'is_premium' => false,
    ]);
    expect($result)->not->toHaveKey('last_name');
    expect($result)->not->toHaveKey('username');
    expect($result)->not->toHaveKey('language_code');
});
