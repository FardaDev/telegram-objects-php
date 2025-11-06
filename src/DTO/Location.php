<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/src/DTO/Location.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-06
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Support\Validator;

/**
 * Represents a point on the map.
 *
 * This object represents a point on the map.
 */
class Location implements ArrayableInterface, SerializableInterface
{
    /**
     * @param float $latitude Latitude as defined by sender
     * @param float $longitude Longitude as defined by sender
     * @param float|null $horizontalAccuracy The radius of uncertainty for the location, measured in meters; 0-1500
     * @param int|null $livePeriod Time relative to the message sending date, during which the location can be updated; in seconds
     * @param int|null $heading The direction in which user is moving, in degrees; 1-360
     * @param int|null $proximityAlertRadius The maximum distance for proximity alerts about approaching another chat member, in meters
     */
    private function __construct(
        private readonly float $latitude,
        private readonly float $longitude,
        private readonly ?float $horizontalAccuracy = null,
        private readonly ?int $livePeriod = null,
        private readonly ?int $heading = null,
        private readonly ?int $proximityAlertRadius = null,
    ) {
    }

    /**
     * Create a Location instance from array data.
     *
     * @param array<string, mixed> $data The location data from Telegram API
     * @return self
     * @throws \Telegram\Objects\Exceptions\ValidationException If required fields are missing or invalid
     */
    public static function fromArray(array $data): self
    {
        Validator::requireField($data, 'latitude', 'Location');
        Validator::requireField($data, 'longitude', 'Location');

        $latitude = Validator::getValue($data, 'latitude', null, 'float');
        $longitude = Validator::getValue($data, 'longitude', null, 'float');
        $horizontalAccuracy = Validator::getValue($data, 'horizontal_accuracy', null, 'float');
        $livePeriod = Validator::getValue($data, 'live_period', null, 'int');
        $heading = Validator::getValue($data, 'heading', null, 'int');
        $proximityAlertRadius = Validator::getValue($data, 'proximity_alert_radius', null, 'int');

        // Validate latitude and longitude ranges
        Validator::validateRange($latitude, 'latitude', -90.0, 90.0);
        Validator::validateRange($longitude, 'longitude', -180.0, 180.0);

        if ($horizontalAccuracy !== null) {
            Validator::validateRange($horizontalAccuracy, 'horizontal_accuracy', 0.0, 1500.0);
        }

        if ($livePeriod !== null) {
            Validator::validateRange($livePeriod, 'live_period', 60, 86400);
        }

        if ($heading !== null) {
            Validator::validateRange($heading, 'heading', 1, 360);
        }

        if ($proximityAlertRadius !== null) {
            Validator::validateRange($proximityAlertRadius, 'proximity_alert_radius', 1, 100000);
        }

        return new self(
            latitude: $latitude,
            longitude: $longitude,
            horizontalAccuracy: $horizontalAccuracy,
            livePeriod: $livePeriod,
            heading: $heading,
            proximityAlertRadius: $proximityAlertRadius,
        );
    }

    /**
     * Get the latitude.
     *
     * @return float
     */
    public function latitude(): float
    {
        return $this->latitude;
    }

    /**
     * Get the longitude.
     *
     * @return float
     */
    public function longitude(): float
    {
        return $this->longitude;
    }

    /**
     * Get the horizontal accuracy.
     *
     * @return float|null
     */
    public function horizontalAccuracy(): ?float
    {
        return $this->horizontalAccuracy;
    }

    /**
     * Get the live period.
     *
     * @return int|null
     */
    public function livePeriod(): ?int
    {
        return $this->livePeriod;
    }

    /**
     * Get the heading direction.
     *
     * @return int|null
     */
    public function heading(): ?int
    {
        return $this->heading;
    }

    /**
     * Get the proximity alert radius.
     *
     * @return int|null
     */
    public function proximityAlertRadius(): ?int
    {
        return $this->proximityAlertRadius;
    }

    /**
     * Check if this is a live location.
     *
     * @return bool
     */
    public function isLive(): bool
    {
        return $this->livePeriod !== null;
    }

    /**
     * Check if this location has heading information.
     *
     * @return bool
     */
    public function hasHeading(): bool
    {
        return $this->heading !== null;
    }

    /**
     * Check if this location has proximity alerts enabled.
     *
     * @return bool
     */
    public function hasProximityAlert(): bool
    {
        return $this->proximityAlertRadius !== null;
    }

    /**
     * Get a formatted coordinate string.
     *
     * @return string
     */
    public function formatCoordinates(): string
    {
        return sprintf('%.6f, %.6f', $this->latitude, $this->longitude);
    }

    /**
     * Convert the Location to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'horizontal_accuracy' => $this->horizontalAccuracy,
            'live_period' => $this->livePeriod,
            'heading' => $this->heading,
            'proximity_alert_radius' => $this->proximityAlertRadius,
        ], fn ($value) => $value !== null);
    }
}
