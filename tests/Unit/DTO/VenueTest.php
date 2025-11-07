<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: tests/Unit/DTO/VenueTest.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-07
 */

use Telegram\Objects\DTO\Location;
use Telegram\Objects\DTO\Venue;
use Telegram\Objects\Exceptions\ValidationException;

it('can create venue from array with minimal fields', function () {
    $venue = Venue::fromArray([
        'location' => [
            'latitude' => 40.7128,
            'longitude' => -74.0060,
        ],
        'title' => 'Central Park',
        'address' => 'New York, NY 10024, USA',
    ]);

    expect($venue->location())->toBeInstanceOf(Location::class);
    expect($venue->title())->toBe('Central Park');
    expect($venue->address())->toBe('New York, NY 10024, USA');
    expect($venue->foursquareId())->toBeNull();
    expect($venue->foursquareType())->toBeNull();
    expect($venue->googlePlaceId())->toBeNull();
    expect($venue->googlePlaceType())->toBeNull();
    expect($venue->hasFoursquareInfo())->toBeFalse();
    expect($venue->hasGooglePlacesInfo())->toBeFalse();
});

it('can create venue from array with all fields', function () {
    $venue = Venue::fromArray([
        'location' => [
            'latitude' => 40.7128,
            'longitude' => -74.0060,
        ],
        'title' => 'Central Park',
        'address' => 'New York, NY 10024, USA',
        'foursquare_id' => '412d2800f964a520df0c1fe3',
        'foursquare_type' => 'arts_entertainment/park',
        'google_place_id' => 'ChIJ4zGFAZpYwokRGUGph3Mf37k',
        'google_place_type' => 'park',
    ]);

    expect($venue->location())->toBeInstanceOf(Location::class);
    expect($venue->title())->toBe('Central Park');
    expect($venue->address())->toBe('New York, NY 10024, USA');
    expect($venue->foursquareId())->toBe('412d2800f964a520df0c1fe3');
    expect($venue->foursquareType())->toBe('arts_entertainment/park');
    expect($venue->googlePlaceId())->toBe('ChIJ4zGFAZpYwokRGUGph3Mf37k');
    expect($venue->googlePlaceType())->toBe('park');
    expect($venue->hasFoursquareInfo())->toBeTrue();
    expect($venue->hasGooglePlacesInfo())->toBeTrue();
});

it('can convert to array', function () {
    $venue = Venue::fromArray([
        'location' => [
            'latitude' => 40.7128,
            'longitude' => -74.0060,
        ],
        'title' => 'Central Park',
        'address' => 'New York, NY 10024, USA',
        'foursquare_id' => '412d2800f964a520df0c1fe3',
        'foursquare_type' => 'arts_entertainment/park',
        'google_place_id' => 'ChIJ4zGFAZpYwokRGUGph3Mf37k',
        'google_place_type' => 'park',
    ]);

    $array = $venue->toArray();

    expect($array)->toHaveKey('location');
    expect($array)->toHaveKey('title', 'Central Park');
    expect($array)->toHaveKey('address', 'New York, NY 10024, USA');
    expect($array)->toHaveKey('foursquare_id', '412d2800f964a520df0c1fe3');
    expect($array)->toHaveKey('foursquare_type', 'arts_entertainment/park');
    expect($array)->toHaveKey('google_place_id', 'ChIJ4zGFAZpYwokRGUGph3Mf37k');
    expect($array)->toHaveKey('google_place_type', 'park');
});

it('filters null values in toArray', function () {
    $venue = Venue::fromArray([
        'location' => [
            'latitude' => 40.7128,
            'longitude' => -74.0060,
        ],
        'title' => 'Central Park',
        'address' => 'New York, NY 10024, USA',
    ]);

    $array = $venue->toArray();

    expect($array)->not->toHaveKey('foursquare_id');
    expect($array)->not->toHaveKey('foursquare_type');
    expect($array)->not->toHaveKey('google_place_id');
    expect($array)->not->toHaveKey('google_place_type');
});

it('can format address', function () {
    $venue = Venue::fromArray([
        'location' => [
            'latitude' => 40.7128,
            'longitude' => -74.0060,
        ],
        'title' => 'Central Park',
        'address' => 'New York, NY 10024, USA',
    ]);

    expect($venue->formatAddress())->toBe("Central Park\nNew York, NY 10024, USA");
});

it('throws exception for missing location', function () {
    Venue::fromArray([
        'title' => 'Central Park',
        'address' => 'New York, NY 10024, USA',
    ]);
})->throws(ValidationException::class, "Missing required field 'location'");

it('throws exception for missing title', function () {
    Venue::fromArray([
        'location' => [
            'latitude' => 40.7128,
            'longitude' => -74.0060,
        ],
        'address' => 'New York, NY 10024, USA',
    ]);
})->throws(ValidationException::class, "Missing required field 'title'");

it('throws exception for missing address', function () {
    Venue::fromArray([
        'location' => [
            'latitude' => 40.7128,
            'longitude' => -74.0060,
        ],
        'title' => 'Central Park',
    ]);
})->throws(ValidationException::class, "Missing required field 'address'");

it('throws exception for empty title', function () {
    Venue::fromArray([
        'location' => [
            'latitude' => 40.7128,
            'longitude' => -74.0060,
        ],
        'title' => '   ',
        'address' => 'New York, NY 10024, USA',
    ]);
})->throws(InvalidArgumentException::class, 'Venue title cannot be empty');

it('throws exception for empty address', function () {
    Venue::fromArray([
        'location' => [
            'latitude' => 40.7128,
            'longitude' => -74.0060,
        ],
        'title' => 'Central Park',
        'address' => '   ',
    ]);
})->throws(InvalidArgumentException::class, 'Venue address cannot be empty');
