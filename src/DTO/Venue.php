<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/src/DTO/Venue.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-06
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Support\Validator;

/**
 * Represents a venue.
 *
 * This object represents a venue.
 */
class Venue implements ArrayableInterface, SerializableInterface
{
    /**
     * @param Location $location Venue location. Can't be a live location
     * @param string $title Name of the venue
     * @param string $address Address of the venue
     * @param string|null $foursquareId Foursquare identifier of the venue
     * @param string|null $foursquareType Foursquare type of the venue
     * @param string|null $googlePlaceId Google Places identifier of the venue
     * @param string|null $googlePlaceType Google Places type of the venue
     */
    private function __construct(
        private readonly Location $location,
        private readonly string $title,
        private readonly string $address,
        private readonly ?string $foursquareId = null,
        private readonly ?string $foursquareType = null,
        private readonly ?string $googlePlaceId = null,
        private readonly ?string $googlePlaceType = null,
    ) {
    }

    /**
     * Create a Venue instance from array data.
     *
     * @param array<string, mixed> $data The venue data from Telegram API
     * @return self
     */
    public static function fromArray(array $data): self
    {
        Validator::requireField($data, 'location', 'Venue');
        Validator::requireField($data, 'title', 'Venue');
        Validator::requireField($data, 'address', 'Venue');

        $locationData = Validator::getValue($data, 'location', null, 'array');
        $location = Location::fromArray($locationData);

        $title = Validator::getValue($data, 'title', null, 'string');
        $address = Validator::getValue($data, 'address', null, 'string');
        $foursquareId = Validator::getValue($data, 'foursquare_id', null, 'string');
        $foursquareType = Validator::getValue($data, 'foursquare_type', null, 'string');
        $googlePlaceId = Validator::getValue($data, 'google_place_id', null, 'string');
        $googlePlaceType = Validator::getValue($data, 'google_place_type', null, 'string');

        // Validate title and address are not empty
        if (trim($title) === '') {
            throw new \InvalidArgumentException('Venue title cannot be empty');
        }

        if (trim($address) === '') {
            throw new \InvalidArgumentException('Venue address cannot be empty');
        }

        return new self(
            location: $location,
            title: $title,
            address: $address,
            foursquareId: $foursquareId,
            foursquareType: $foursquareType,
            googlePlaceId: $googlePlaceId,
            googlePlaceType: $googlePlaceType,
        );
    }

    /**
     * Get the venue location.
     *
     * @return Location
     */
    public function location(): Location
    {
        return $this->location;
    }

    /**
     * Get the name of the venue.
     *
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * Get the address of the venue.
     *
     * @return string
     */
    public function address(): string
    {
        return $this->address;
    }

    /**
     * Get the Foursquare identifier of the venue.
     *
     * @return string|null
     */
    public function foursquareId(): ?string
    {
        return $this->foursquareId;
    }

    /**
     * Get the Foursquare type of the venue.
     *
     * @return string|null
     */
    public function foursquareType(): ?string
    {
        return $this->foursquareType;
    }

    /**
     * Get the Google Places identifier of the venue.
     *
     * @return string|null
     */
    public function googlePlaceId(): ?string
    {
        return $this->googlePlaceId;
    }

    /**
     * Get the Google Places type of the venue.
     *
     * @return string|null
     */
    public function googlePlaceType(): ?string
    {
        return $this->googlePlaceType;
    }

    /**
     * Check if this venue has Foursquare information.
     *
     * @return bool
     */
    public function hasFoursquareInfo(): bool
    {
        return $this->foursquareId !== null || $this->foursquareType !== null;
    }

    /**
     * Check if this venue has Google Places information.
     *
     * @return bool
     */
    public function hasGooglePlacesInfo(): bool
    {
        return $this->googlePlaceId !== null || $this->googlePlaceType !== null;
    }

    /**
     * Get a formatted address string.
     *
     * @return string
     */
    public function formatAddress(): string
    {
        return "{$this->title}\n{$this->address}";
    }

    /**
     * Convert the Venue to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'location' => $this->location->toArray(),
            'title' => $this->title,
            'address' => $this->address,
            'foursquare_id' => $this->foursquareId,
            'foursquare_type' => $this->foursquareType,
            'google_place_id' => $this->googlePlaceId,
            'google_place_type' => $this->googlePlaceType,
        ], fn ($value) => $value !== null);
    }
}
