<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/src/DTO/Contact.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-06
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Support\Validator;

/**
 * Represents a phone contact.
 *
 * This object represents a phone contact.
 */
class Contact implements ArrayableInterface, SerializableInterface
{
    /**
     * @param string $phoneNumber Contact's phone number
     * @param string $firstName Contact's first name
     * @param string|null $lastName Contact's last name
     * @param int|null $userId Contact's user identifier in Telegram
     * @param string|null $vcard Additional data about the contact in the form of a vCard
     */
    private function __construct(
        private readonly string $phoneNumber,
        private readonly string $firstName,
        private readonly ?string $lastName = null,
        private readonly ?int $userId = null,
        private readonly ?string $vcard = null,
    ) {
    }

    /**
     * Create a Contact instance from array data.
     *
     * @param array<string, mixed> $data The contact data from Telegram API
     * @return self
     */
    public static function fromArray(array $data): self
    {
        Validator::requireField($data, 'phone_number', 'Contact');
        Validator::requireField($data, 'first_name', 'Contact');

        $phoneNumber = Validator::getValue($data, 'phone_number', null, 'string');
        $firstName = Validator::getValue($data, 'first_name', null, 'string');
        $lastName = Validator::getValue($data, 'last_name', null, 'string');
        $userId = Validator::getValue($data, 'user_id', null, 'int');
        $vcard = Validator::getValue($data, 'vcard', null, 'string');

        // Validate phone number is not empty
        if (trim($phoneNumber) === '') {
            throw new \InvalidArgumentException('Contact phone_number cannot be empty');
        }

        // Validate first name is not empty
        if (trim($firstName) === '') {
            throw new \InvalidArgumentException('Contact first_name cannot be empty');
        }

        return new self(
            phoneNumber: $phoneNumber,
            firstName: $firstName,
            lastName: $lastName,
            userId: $userId,
            vcard: $vcard,
        );
    }

    /**
     * Get the contact's phone number.
     *
     * @return string
     */
    public function phoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * Get the contact's first name.
     *
     * @return string
     */
    public function firstName(): string
    {
        return $this->firstName;
    }

    /**
     * Get the contact's last name.
     *
     * @return string|null
     */
    public function lastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * Get the contact's user identifier in Telegram.
     *
     * @return int|null
     */
    public function userId(): ?int
    {
        return $this->userId;
    }

    /**
     * Get the additional data about the contact in the form of a vCard.
     *
     * @return string|null
     */
    public function vcard(): ?string
    {
        return $this->vcard;
    }

    /**
     * Get the full name of the contact.
     *
     * @return string
     */
    public function fullName(): string
    {
        if ($this->lastName !== null) {
            return "{$this->firstName} {$this->lastName}";
        }

        return $this->firstName;
    }

    /**
     * Check if this contact is a Telegram user.
     *
     * @return bool
     */
    public function isTelegramUser(): bool
    {
        return $this->userId !== null;
    }

    /**
     * Check if this contact has a vCard.
     *
     * @return bool
     */
    public function hasVcard(): bool
    {
        return $this->vcard !== null;
    }

    /**
     * Convert the Contact to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'phone_number' => $this->phoneNumber,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'user_id' => $this->userId,
            'vcard' => $this->vcard,
        ], fn ($value) => $value !== null);
    }
}
