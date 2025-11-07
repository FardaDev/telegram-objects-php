<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: src/DTO/PreCheckoutQuery.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-07
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Support\Validator;

/**
 * Represents an incoming pre-checkout query.
 *
 * This object contains information about an incoming pre-checkout query.
 */
class PreCheckoutQuery implements ArrayableInterface, SerializableInterface
{
    /**
     * @param string $id Unique query identifier
     * @param User $from User who sent the query
     * @param string $currency Three-letter ISO 4217 currency code
     * @param int $totalAmount Total price in the smallest units of the currency (integer, not float/double)
     * @param string $invoicePayload Bot specified invoice payload
     * @param string|null $shippingOptionId Identifier of the shipping option chosen by the user
     * @param OrderInfo|null $orderInfo Order info provided by the user
     */
    private function __construct(
        private readonly string $id,
        private readonly User $from,
        private readonly string $currency,
        private readonly int $totalAmount,
        private readonly string $invoicePayload,
        private readonly ?string $shippingOptionId = null,
        private readonly ?OrderInfo $orderInfo = null,
    ) {
    }

    /**
     * Create a PreCheckoutQuery instance from array data.
     *
     * @param array<string, mixed> $data The pre-checkout query data from Telegram API
     * @return self
     */
    public static function fromArray(array $data): self
    {
        Validator::requireField($data, 'id', 'PreCheckoutQuery');
        Validator::requireField($data, 'from', 'PreCheckoutQuery');
        Validator::requireField($data, 'currency', 'PreCheckoutQuery');
        Validator::requireField($data, 'total_amount', 'PreCheckoutQuery');
        Validator::requireField($data, 'invoice_payload', 'PreCheckoutQuery');

        $id = Validator::getValue($data, 'id', null, 'string');
        $currency = Validator::getValue($data, 'currency', null, 'string');
        $totalAmount = Validator::getValue($data, 'total_amount', null, 'int');
        $invoicePayload = Validator::getValue($data, 'invoice_payload', null, 'string');
        $shippingOptionId = Validator::getValue($data, 'shipping_option_id', null, 'string');

        // Validate currency code format (ISO 4217)
        Validator::validateStringLength($currency, 'currency', 3, 3);

        // Validate total amount is non-negative
        Validator::validateRange($totalAmount, 'total_amount', 0);

        $from = User::fromArray($data['from']);

        $orderInfo = null;
        if (isset($data['order_info']) && is_array($data['order_info'])) {
            $orderInfo = OrderInfo::fromArray($data['order_info']);
        }

        return new self(
            id: $id,
            from: $from,
            currency: $currency,
            totalAmount: $totalAmount,
            invoicePayload: $invoicePayload,
            shippingOptionId: $shippingOptionId,
            orderInfo: $orderInfo,
        );
    }

    /**
     * Get the unique query identifier.
     *
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * Get the user who sent the query.
     *
     * @return User
     */
    public function from(): User
    {
        return $this->from;
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
     * Get the bot specified invoice payload.
     *
     * @return string
     */
    public function invoicePayload(): string
    {
        return $this->invoicePayload;
    }

    /**
     * Get the identifier of the shipping option chosen by the user.
     *
     * @return string|null
     */
    public function shippingOptionId(): ?string
    {
        return $this->shippingOptionId;
    }

    /**
     * Get the order info provided by the user.
     *
     * @return OrderInfo|null
     */
    public function orderInfo(): ?OrderInfo
    {
        return $this->orderInfo;
    }

    /**
     * Check if the query has a shipping option.
     *
     * @return bool
     */
    public function hasShippingOption(): bool
    {
        return $this->shippingOptionId !== null;
    }

    /**
     * Check if the query has order info.
     *
     * @return bool
     */
    public function hasOrderInfo(): bool
    {
        return $this->orderInfo !== null;
    }

    /**
     * Get the total amount formatted as a decimal string.
     *
     * @param int $decimalPlaces Number of decimal places for the currency (default: 2)
     * @return string
     */
    public function formatAmount(int $decimalPlaces = 2): string
    {
        return number_format($this->totalAmount / (10 ** $decimalPlaces), $decimalPlaces);
    }

    /**
     * Convert the PreCheckoutQuery to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'from' => $this->from->toArray(),
            'currency' => $this->currency,
            'total_amount' => $this->totalAmount,
            'invoice_payload' => $this->invoicePayload,
            'shipping_option_id' => $this->shippingOptionId,
            'order_info' => $this->orderInfo?->toArray(),
        ], fn ($value) => $value !== null);
    }
}
