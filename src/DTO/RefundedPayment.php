<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/src/DTO/RefundedPayment.php
 * Telegraph commit: [commit_hash]
 * Date: [date]
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Support\Validator;

/**
 * Represents a refunded payment.
 *
 * This object contains basic information about a refunded payment.
 */
class RefundedPayment implements ArrayableInterface, SerializableInterface
{
    /**
     * @param string $currency Three-letter ISO 4217 currency code
     * @param int $totalAmount Total refunded price in the smallest units of the currency (integer, not float/double)
     * @param string $invoicePayload Bot specified invoice payload
     * @param string $telegramPaymentChargeId Telegram payment identifier
     * @param string|null $providerPaymentChargeId Provider payment identifier
     */
    private function __construct(
        private readonly string $currency,
        private readonly int $totalAmount,
        private readonly string $invoicePayload,
        private readonly string $telegramPaymentChargeId,
        private readonly ?string $providerPaymentChargeId = null,
    ) {
    }

    /**
     * Create a RefundedPayment instance from array data.
     *
     * @param array<string, mixed> $data The refunded payment data from Telegram API
     * @return self
     */
    public static function fromArray(array $data): self
    {
        Validator::requireField($data, 'currency', 'RefundedPayment');
        Validator::requireField($data, 'total_amount', 'RefundedPayment');
        Validator::requireField($data, 'invoice_payload', 'RefundedPayment');
        Validator::requireField($data, 'telegram_payment_charge_id', 'RefundedPayment');

        $currency = Validator::getValue($data, 'currency', null, 'string');
        $totalAmount = Validator::getValue($data, 'total_amount', null, 'int');
        $invoicePayload = Validator::getValue($data, 'invoice_payload', null, 'string');
        $telegramPaymentChargeId = Validator::getValue($data, 'telegram_payment_charge_id', null, 'string');
        $providerPaymentChargeId = Validator::getValue($data, 'provider_payment_charge_id', null, 'string');

        // Validate currency code format (ISO 4217)
        Validator::validateStringLength($currency, 'currency', 3, 3);

        // Validate total amount is non-negative
        Validator::validateRange($totalAmount, 'total_amount', 0);

        return new self(
            currency: $currency,
            totalAmount: $totalAmount,
            invoicePayload: $invoicePayload,
            telegramPaymentChargeId: $telegramPaymentChargeId,
            providerPaymentChargeId: $providerPaymentChargeId,
        );
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
     * Get the total refunded price in the smallest units of the currency.
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
     * Get the Telegram payment identifier.
     *
     * @return string
     */
    public function telegramPaymentChargeId(): string
    {
        return $this->telegramPaymentChargeId;
    }

    /**
     * Get the provider payment identifier.
     *
     * @return string|null
     */
    public function providerPaymentChargeId(): ?string
    {
        return $this->providerPaymentChargeId;
    }

    /**
     * Check if the refund has a provider payment charge ID.
     *
     * @return bool
     */
    public function hasProviderPaymentChargeId(): bool
    {
        return $this->providerPaymentChargeId !== null;
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
     * Convert the RefundedPayment to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'currency' => $this->currency,
            'total_amount' => $this->totalAmount,
            'invoice_payload' => $this->invoicePayload,
            'telegram_payment_charge_id' => $this->telegramPaymentChargeId,
            'provider_payment_charge_id' => $this->providerPaymentChargeId,
        ], fn ($value) => $value !== null);
    }
}
