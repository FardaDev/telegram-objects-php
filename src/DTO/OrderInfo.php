<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: src/DTO/OrderInfo.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-07
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Support\Validator;

/**
 * Represents information about an order.
 *
 * This object contains information about the order for a payment.
 */
class OrderInfo implements ArrayableInterface, SerializableInterface
{
    /**
     * @param string|null $name User name
     * @param string|null $phoneNumber User's phone number
     * @param string|null $email User email
     * @param ShippingAddress|null $shippingAddress User shipping address
     */
    private function __construct(
        private readonly ?string $name = null,
        private readonly ?string $phoneNumber = null,
        private readonly ?string $email = null,
        private readonly ?ShippingAddress $shippingAddress = null,
    ) {
    }

    /**
     * Create an OrderInfo instance from array data.
     *
     * @param array<string, mixed> $data The order info data from Telegram API
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $name = Validator::getValue($data, 'name', null, 'string');
        $phoneNumber = Validator::getValue($data, 'phone_number', null, 'string');
        $email = Validator::getValue($data, 'email', null, 'string');

        $shippingAddress = null;
        if (isset($data['shipping_address']) && is_array($data['shipping_address'])) {
            $shippingAddress = ShippingAddress::fromArray($data['shipping_address']);
        }

        // Validate email format if provided
        if ($email !== null && $email !== '') {
            Validator::validateEmail($email, 'email');
        }

        return new self(
            name: $name,
            phoneNumber: $phoneNumber,
            email: $email,
            shippingAddress: $shippingAddress,
        );
    }

    /**
     * Get the user name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->name;
    }

    /**
     * Get the user's phone number.
     *
     * @return string|null
     */
    public function phoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     * Get the user email.
     *
     * @return string|null
     */
    public function email(): ?string
    {
        return $this->email;
    }

    /**
     * Get the user shipping address.
     *
     * @return ShippingAddress|null
     */
    public function shippingAddress(): ?ShippingAddress
    {
        return $this->shippingAddress;
    }

    /**
     * Check if the order has a name.
     *
     * @return bool
     */
    public function hasName(): bool
    {
        return $this->name !== null;
    }

    /**
     * Check if the order has a phone number.
     *
     * @return bool
     */
    public function hasPhoneNumber(): bool
    {
        return $this->phoneNumber !== null;
    }

    /**
     * Check if the order has an email.
     *
     * @return bool
     */
    public function hasEmail(): bool
    {
        return $this->email !== null;
    }

    /**
     * Check if the order has a shipping address.
     *
     * @return bool
     */
    public function hasShippingAddress(): bool
    {
        return $this->shippingAddress !== null;
    }

    /**
     * Convert the OrderInfo to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'phone_number' => $this->phoneNumber,
            'email' => $this->email,
            'shipping_address' => $this->shippingAddress?->toArray(),
        ], fn ($value) => $value !== null);
    }
}
