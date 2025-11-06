<?php

declare(strict_types=1);

use Telegram\Objects\DTO\Location;
use Telegram\Objects\Exceptions\ValidationException;

it('can create location from array with minimal fields', function () {
    $location = Location::fromArray([
        'latitude' => 40.7128,
        'longitude' => -74.0060,
    ]);

    expect($location->latitude())->toBe(40.7128);
    expect($location->longitude())->toBe(-74.0060);
    expect($location->horizontalAccuracy())->toBeNull();
    expect($location->livePeriod())->toBeNull();
    expect($location->heading())->toBeNull();
    expect($location->proximityAlertRadius())->toBeNull();
    expect($location->isLive())->toBeFalse();
    expect($location->hasHeading())->toBeFalse();
    expect($location->hasProximityAlert())->toBeFalse();
});

it('can create location from array with all fields', function () {
    $location = Location::fromArray([
        'latitude' => 40.7128,
        'longitude' => -74.0060,
        'horizontal_accuracy' => 10.5,
        'live_period' => 3600,
        'heading' => 180,
        'proximity_alert_radius' => 1000,
    ]);

    expect($location->latitude())->toBe(40.7128);
    expect($location->longitude())->toBe(-74.0060);
    expect($location->horizontalAccuracy())->toBe(10.5);
    expect($location->livePeriod())->toBe(3600);
    expect($location->heading())->toBe(180);
    expect($location->proximityAlertRadius())->toBe(1000);
    expect($location->isLive())->toBeTrue();
    expect($location->hasHeading())->toBeTrue();
    expect($location->hasProximityAlert())->toBeTrue();
});

it('can convert to array', function () {
    $location = Location::fromArray([
        'latitude' => 40.7128,
        'longitude' => -74.0060,
        'horizontal_accuracy' => 10.5,
        'live_period' => 3600,
        'heading' => 180,
        'proximity_alert_radius' => 1000,
    ]);

    $array = $location->toArray();

    expect($array)->toHaveKey('latitude', 40.7128);
    expect($array)->toHaveKey('longitude', -74.0060);
    expect($array)->toHaveKey('horizontal_accuracy', 10.5);
    expect($array)->toHaveKey('live_period', 3600);
    expect($array)->toHaveKey('heading', 180);
    expect($array)->toHaveKey('proximity_alert_radius', 1000);
});

it('filters null values in toArray', function () {
    $location = Location::fromArray([
        'latitude' => 40.7128,
        'longitude' => -74.0060,
    ]);

    $array = $location->toArray();

    expect($array)->not->toHaveKey('horizontal_accuracy');
    expect($array)->not->toHaveKey('live_period');
    expect($array)->not->toHaveKey('heading');
    expect($array)->not->toHaveKey('proximity_alert_radius');
});

it('can format coordinates', function () {
    $location = Location::fromArray([
        'latitude' => 40.712800,
        'longitude' => -74.006000,
    ]);

    expect($location->formatCoordinates())->toBe('40.712800, -74.006000');
});

it('throws exception for missing latitude', function () {
    Location::fromArray([
        'longitude' => -74.0060,
    ]);
})->throws(ValidationException::class, "Missing required field 'latitude'");

it('throws exception for missing longitude', function () {
    Location::fromArray([
        'latitude' => 40.7128,
    ]);
})->throws(ValidationException::class, "Missing required field 'longitude'");

it('throws exception for invalid latitude range', function () {
    Location::fromArray([
        'latitude' => 91.0,
        'longitude' => -74.0060,
    ]);
})->throws(ValidationException::class, 'allowed range: -90 to 90');

it('throws exception for invalid longitude range', function () {
    Location::fromArray([
        'latitude' => 40.7128,
        'longitude' => 181.0,
    ]);
})->throws(ValidationException::class, 'allowed range: -180 to 180');

it('throws exception for invalid horizontal accuracy', function () {
    Location::fromArray([
        'latitude' => 40.7128,
        'longitude' => -74.0060,
        'horizontal_accuracy' => 2000.0,
    ]);
})->throws(ValidationException::class, 'allowed range: 0 to 1500');

it('throws exception for invalid live period', function () {
    Location::fromArray([
        'latitude' => 40.7128,
        'longitude' => -74.0060,
        'live_period' => 30,
    ]);
})->throws(ValidationException::class, 'allowed range: 60 to 86400');

it('throws exception for invalid heading', function () {
    Location::fromArray([
        'latitude' => 40.7128,
        'longitude' => -74.0060,
        'heading' => 0,
    ]);
})->throws(ValidationException::class, 'allowed range: 1 to 360');
