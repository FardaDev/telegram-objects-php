<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/tests/Unit/DTO/ContactTest.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-07
 */

use Telegram\Objects\DTO\Contact;
use Telegram\Objects\Exceptions\ValidationException;

it('can create contact from array with minimal fields', function () {
    $contact = Contact::fromArray([
        'phone_number' => '+1234567890',
        'first_name' => 'John',
    ]);

    expect($contact->phoneNumber())->toBe('+1234567890');
    expect($contact->firstName())->toBe('John');
    expect($contact->lastName())->toBeNull();
    expect($contact->userId())->toBeNull();
    expect($contact->vcard())->toBeNull();
    expect($contact->fullName())->toBe('John');
    expect($contact->isTelegramUser())->toBeFalse();
    expect($contact->hasVcard())->toBeFalse();
});

it('can create contact from array with all fields', function () {
    $contact = Contact::fromArray([
        'phone_number' => '+1234567890',
        'first_name' => 'John',
        'last_name' => 'Doe',
        'user_id' => 123456789,
        'vcard' => 'BEGIN:VCARD\nVERSION:3.0\nFN:John Doe\nEND:VCARD',
    ]);

    expect($contact->phoneNumber())->toBe('+1234567890');
    expect($contact->firstName())->toBe('John');
    expect($contact->lastName())->toBe('Doe');
    expect($contact->userId())->toBe(123456789);
    expect($contact->vcard())->toBe('BEGIN:VCARD\nVERSION:3.0\nFN:John Doe\nEND:VCARD');
    expect($contact->fullName())->toBe('John Doe');
    expect($contact->isTelegramUser())->toBeTrue();
    expect($contact->hasVcard())->toBeTrue();
});

it('can convert to array', function () {
    $contact = Contact::fromArray([
        'phone_number' => '+1234567890',
        'first_name' => 'John',
        'last_name' => 'Doe',
        'user_id' => 123456789,
        'vcard' => 'BEGIN:VCARD\nVERSION:3.0\nFN:John Doe\nEND:VCARD',
    ]);

    $array = $contact->toArray();

    expect($array)->toHaveKey('phone_number', '+1234567890');
    expect($array)->toHaveKey('first_name', 'John');
    expect($array)->toHaveKey('last_name', 'Doe');
    expect($array)->toHaveKey('user_id', 123456789);
    expect($array)->toHaveKey('vcard', 'BEGIN:VCARD\nVERSION:3.0\nFN:John Doe\nEND:VCARD');
});

it('filters null values in toArray', function () {
    $contact = Contact::fromArray([
        'phone_number' => '+1234567890',
        'first_name' => 'John',
    ]);

    $array = $contact->toArray();

    expect($array)->not->toHaveKey('last_name');
    expect($array)->not->toHaveKey('user_id');
    expect($array)->not->toHaveKey('vcard');
});

it('throws exception for missing phone_number', function () {
    Contact::fromArray([
        'first_name' => 'John',
    ]);
})->throws(ValidationException::class, "Missing required field 'phone_number'");

it('throws exception for missing first_name', function () {
    Contact::fromArray([
        'phone_number' => '+1234567890',
    ]);
})->throws(ValidationException::class, "Missing required field 'first_name'");

it('throws exception for empty phone_number', function () {
    Contact::fromArray([
        'phone_number' => '   ',
        'first_name' => 'John',
    ]);
})->throws(InvalidArgumentException::class, 'Contact phone_number cannot be empty');

it('throws exception for empty first_name', function () {
    Contact::fromArray([
        'phone_number' => '+1234567890',
        'first_name' => '   ',
    ]);
})->throws(InvalidArgumentException::class, 'Contact first_name cannot be empty');
