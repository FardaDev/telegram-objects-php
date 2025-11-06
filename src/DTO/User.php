<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/src/DTO/User.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-06
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Support\Validator;

/**
 * Represents a Telegram user.
 *
 * This object represents a Telegram user or bot.
 */
class User implements ArrayableInterface, SerializableInterface
{
    /**
     * @param int $id Unique identifier for this user or bot
     * @param bool $isBot True, if this user is a bot
     * @param string $firstName User's or bot's first name
     * @param string|null $lastName User's or bot's last name
     * @param string|null $username User's or bot's username
     * @param string|null $languageCode IETF language tag of the user's language
     * @param bool $isPremium True, if this user is a Telegram Premium user
     */
    private function __construct(
        private readonly int $id,
        private readonly bool $isBot,
        private readonly string $firstName,
        private readonly ?string $lastName = null,
        private readonly ?string $username = null,
        private readonly ?string $languageCode = null,
        private readonly bool $isPremium = false,
    ) {
    }

    /**
     * Create a User instance from array data.
     *
     * @param array<string, mixed> $data The user data from Telegram API
     * @return self
     * @throws \Telegram\Objects\Exceptions\ValidationException If required fields are missing or invalid
     */
    public static function fromArray(array $data): self
    {
        Validator::requireField($data, 'id', 'User');
        Validator::requireField($data, 'first_name', 'User');

        $id = Validator::getValue($data, 'id', null, 'int');
        $firstName = Validator::getValue($data, 'first_name', '', 'string');
        $isBot = Validator::getValue($data, 'is_bot', false, 'bool');
        $lastName = Validator::getValue($data, 'last_name', null, 'string');
        $username = Validator::getValue($data, 'username', null, 'string');
        $languageCode = Validator::getValue($data, 'language_code', null, 'string');
        $isPremium = Validator::getValue($data, 'is_premium', false, 'bool');

        return new self(
            id: $id,
            isBot: $isBot,
            firstName: $firstName,
            lastName: $lastName,
            username: $username,
            languageCode: $languageCode,
            isPremium: $isPremium,
        );
    }

    /**
     * Get the unique identifier for this user or bot.
     *
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }

    /**
     * Check if this user is a bot.
     *
     * @return bool
     */
    public function isBot(): bool
    {
        return $this->isBot;
    }

    /**
     * Get the user's or bot's first name.
     *
     * @return string
     */
    public function firstName(): string
    {
        return $this->firstName;
    }

    /**
     * Get the user's or bot's last name.
     *
     * @return string|null
     */
    public function lastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * Get the user's or bot's username.
     *
     * @return string|null
     */
    public function username(): ?string
    {
        return $this->username;
    }

    /**
     * Get the IETF language tag of the user's language.
     *
     * @return string|null
     */
    public function languageCode(): ?string
    {
        return $this->languageCode;
    }

    /**
     * Check if this user is a Telegram Premium user.
     *
     * @return bool
     */
    public function isPremium(): bool
    {
        return $this->isPremium;
    }

    /**
     * Get the user's full name (first name + last name).
     *
     * @return string
     */
    public function fullName(): string
    {
        $parts = array_filter([$this->firstName, $this->lastName]);

        return implode(' ', $parts);
    }

    /**
     * Convert the User to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'is_bot' => $this->isBot,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'username' => $this->username,
            'language_code' => $this->languageCode,
            'is_premium' => $this->isPremium,
        ], fn ($value) => $value !== null && $value !== '');
    }
}
