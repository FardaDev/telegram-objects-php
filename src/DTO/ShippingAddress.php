<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/src/DTO/ShippingAddress.php
 * Telegraph commit: [commit_hash]
 * Date: [date]
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Support\Validator;

/**
 * Represents a shipping address.
 *
 * This object contains information about the shipping address for an order.
 */
class ShippingAddress implements ArrayableInterface, SerializableInterface
{
    /**
     * @param string $countryCode Two-letter ISO 3166-1 alpha-2 country code
     * @param string $state State, if applicable
     * @param string $city City
     * @param string $streetLine1 First line for the address
     * @param string $streetLine2 Second line for the address
     * @param string $postCode Address post code
     */
    private function __construct(
        private readonly string $countryCode,
        private readonly string $state,
        private readonly string $city,
        private readonly string $streetLine1,
        private readonly string $streetLine2,
        private readonly string $postCode,
    ) {
    }

    /**
     * Create a ShippingAddress instance from array data.
     *
     * @param array<string, mixed> $data The shipping address data from Telegram API
     * @return self
     */
    public static function fromArray(array $data): self
    {
        Validator::requireField($data, 'country_code', 'ShippingAddress');
        Validator::requireField($data, 'state', 'ShippingAddress');
        Validator::requireField($data, 'city', 'ShippingAddress');
        Validator::requireField($data, 'street_line1', 'ShippingAddress');
        Validator::requireField($data, 'street_line2', 'ShippingAddress');
        Validator::requireField($data, 'post_code', 'ShippingAddress');

        $countryCode = Validator::getValue($data, 'country_code', null, 'string');
        $state = Validator::getValue($data, 'state', null, 'string');
        $city = Validator::getValue($data, 'city', null, 'string');
        $streetLine1 = Validator::getValue($data, 'street_line1', null, 'string');
        $streetLine2 = Validator::getValue($data, 'street_line2', null, 'string');
        $postCode = Validator::getValue($data, 'post_code', null, 'string');

        // Validate country code format (ISO 3166-1 alpha-2)
        Validator::validateStringLength($countryCode, 'country_code', 2, 2);

        return new self(
            countryCode: $countryCode,
            state: $state,
            city: $city,
            streetLine1: $streetLine1,
            streetLine2: $streetLine2,
            postCode: $postCode,
        );
    }

    /**
     * Get the two-letter ISO 3166-1 alpha-2 country code.
     *
     * @return string
     */
    public function countryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * Get the state, if applicable.
     *
     * @return string
     */
    public function state(): string
    {
        return $this->state;
    }

    /**
     * Get the city.
     *
     * @return string
     */
    public function city(): string
    {
        return $this->city;
    }

    /**
     * Get the first line for the address.
     *
     * @return string
     */
    public function streetLine1(): string
    {
        return $this->streetLine1;
    }

    /**
     * Get the second line for the address.
     *
     * @return string
     */
    public function streetLine2(): string
    {
        return $this->streetLine2;
    }

    /**
     * Get the address post code.
     *
     * @return string
     */
    public function postCode(): string
    {
        return $this->postCode;
    }

    /**
     * Get the full address as a formatted string.
     *
     * @return string
     */
    public function formatAddress(): string
    {
        $parts = [
            $this->streetLine1,
            $this->streetLine2,
            $this->city,
            $this->state,
            $this->postCode,
            $this->countryCode,
        ];

        return implode(', ', array_filter($parts));
    }

    /**
     * Convert the ShippingAddress to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'country_code' => $this->countryCode,
            'state' => $this->state,
            'city' => $this->city,
            'street_line1' => $this->streetLine1,
            'street_line2' => $this->streetLine2,
            'post_code' => $this->postCode,
        ];
    }
}
