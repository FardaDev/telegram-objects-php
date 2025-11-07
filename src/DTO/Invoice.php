<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: src/DTO/Invoice.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-07
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Support\Validator;

/**
 * Represents an invoice.
 *
 * This object contains basic information about an invoice.
 */
class Invoice implements ArrayableInterface, SerializableInterface
{
    /**
     * @param string $title Product name
     * @param string $description Product description
     * @param string $startParameter Unique bot deep-linking parameter that can be used to generate this invoice
     * @param string $currency Three-letter ISO 4217 currency code
     * @param int $totalAmount Total price in the smallest units of the currency (integer, not float/double)
     */
    private function __construct(
        private readonly string $title,
        private readonly string $description,
        private readonly string $startParameter,
        private readonly string $currency,
        private readonly int $totalAmount,
    ) {
    }

    /**
     * Create an Invoice instance from array data.
     *
     * @param array<string, mixed> $data The invoice data from Telegram API
     * @return self
     */
    public static function fromArray(array $data): self
    {
        Validator::requireField($data, 'title', 'Invoice');
        Validator::requireField($data, 'description', 'Invoice');
        Validator::requireField($data, 'start_parameter', 'Invoice');
        Validator::requireField($data, 'currency', 'Invoice');
        Validator::requireField($data, 'total_amount', 'Invoice');

        $title = Validator::getValue($data, 'title', null, 'string');
        $description = Validator::getValue($data, 'description', null, 'string');
        $startParameter = Validator::getValue($data, 'start_parameter', null, 'string');
        $currency = Validator::getValue($data, 'currency', null, 'string');
        $totalAmount = Validator::getValue($data, 'total_amount', null, 'int');

        // Validate currency code format (ISO 4217)
        Validator::validateStringLength($currency, 'currency', 3, 3);

        // Validate total amount is non-negative
        Validator::validateRange($totalAmount, 'total_amount', 0);

        return new self(
            title: $title,
            description: $description,
            startParameter: $startParameter,
            currency: $currency,
            totalAmount: $totalAmount,
        );
    }

    /**
     * Get the product name.
     *
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * Get the product description.
     *
     * @return string
     */
    public function description(): string
    {
        return $this->description;
    }

    /**
     * Get the unique bot deep-linking parameter.
     *
     * @return string
     */
    public function startParameter(): string
    {
        return $this->startParameter;
    }

    /**
     * Get the three-letter ISO 4217 currency code.
     *
     * @return string
     */
    public function currency(): string
    {
        return $this->currency;
    }

    /**
     * Get the total price in the smallest units of the currency.
     *
     * @return int
     */
    public function totalAmount(): int
    {
        return $this->totalAmount;
    }

    /**
     * Get the total amount formatted as a decimal string.
     * For example, 1234 cents becomes "12.34" for USD.
     *
     * @param int $decimalPlaces Number of decimal places for the currency (default: 2)
     * @return string
     */
    public function formatAmount(int $decimalPlaces = 2): string
    {
        return number_format($this->totalAmount / (10 ** $decimalPlaces), $decimalPlaces);
    }

    /**
     * Convert the Invoice to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'start_parameter' => $this->startParameter,
            'currency' => $this->currency,
            'total_amount' => $this->totalAmount,
        ];
    }
}
