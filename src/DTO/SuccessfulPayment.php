<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/src/DTO/SuccessfulPayment.php
 * Telegraph commit: [commit_hash]
 * Date: [date]
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Support\Validator;

/**
 * Represents a successful payment.
 *
 * This object contains basic information about a successful payment.
 */
class SuccessfulPayment implements ArrayableInterface, SerializableInterface
{
    /**
     * @param string $currency Three-letter ISO 4217 currency code
     * @param int $totalAmount Total price in the smallest units of the currency (integer, not float/double)
     * @param string $invoicePayload Bot specified invoice payload
     * @param string $telegramPaymentChargeId Telegram payment identifier
     * @param string $providerPaymentChargeId Provider payment identifier
     * @param int|null $subscriptionExpirationDate Expiration date of the subscription, in Unix time
     * @param bool $isRecurring True, if the payment is a recurring payment for a subscription
     * @param bool $isFirstRecurring True, if the payment is the first payment for a subscription
     * @param string|null $shippingOptionId Identifier of the shipping option chosen by the user
     * @param OrderInfo|null $orderInfo Order info provided by the user
     */
    private function __construct(
        private readonly string $currency,
        private readonly int $totalAmount,
        private readonly string $invoicePayload,
        private readonly string $telegramPaymentChargeId,
        private readonly string $providerPaymentChargeId,
        private readonly ?int $subscriptionExpirationDate = null,
        private readonly bool $isRecurring = false,
        private readonly bool $isFirstRecurring = false,
        private readonly ?string $shippingOptionId = null,
        private readonly ?OrderInfo $orderInfo = null,
    ) {
    }

    /**
     * Create a SuccessfulPayment instance from array data.
     *
     * @param array<string, mixed> $data The successful payment data from Telegram API
     * @return self
     */
    public static function fromArray(array $data): self
    {
        Validator::requireField($data, 'currency', 'SuccessfulPayment');
        Validator::requireField($data, 'total_amount', 'SuccessfulPayment');
        Validator::requireField($data, 'invoice_payload', 'SuccessfulPayment');
        Validator::requireField($data, 'telegram_payment_charge_id', 'SuccessfulPayment');
        Validator::requireField($data, 'provider_payment_charge_id', 'SuccessfulPayment');

        $currency = Validator::getValue($data, 'currency', null, 'string');
        $totalAmount = Validator::getValue($data, 'total_amount', null, 'int');
        $invoicePayload = Validator::getValue($data, 'invoice_payload', null, 'string');
        $subscriptionExpirationDate = Validator::getValue($data, 'subscription_expiration_date', null, 'int');
        $isRecurring = Validator::getValue($data, 'is_recurring', false, 'bool');
        $isFirstRecurring = Validator::getValue($data, 'is_first_recurring', false, 'bool');
        $shippingOptionId = Validator::getValue($data, 'shipping_option_id', null, 'string');
        $telegramPaymentChargeId = Validator::getValue($data, 'telegram_payment_charge_id', null, 'string');
        $providerPaymentChargeId = Validator::getValue($data, 'provider_payment_charge_id', null, 'string');

        // Validate currency code format (ISO 4217)
        Validator::validateStringLength($currency, 'currency', 3, 3);

        // Validate total amount is non-negative
        Validator::validateRange($totalAmount, 'total_amount', 0);

        $orderInfo = null;
        if (isset($data['order_info']) && is_array($data['order_info'])) {
            $orderInfo = OrderInfo::fromArray($data['order_info']);
        }

        return new self(
            currency: $currency,
            totalAmount: $totalAmount,
            invoicePayload: $invoicePayload,
            telegramPaymentChargeId: $telegramPaymentChargeId,
            providerPaymentChargeId: $providerPaymentChargeId,
            subscriptionExpirationDate: $subscriptionExpirationDate,
            isRecurring: $isRecurring,
            isFirstRecurring: $isFirstRecurring,
            shippingOptionId: $shippingOptionId,
            orderInfo: $orderInfo,
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
     * Get the expiration date of the subscription, in Unix time.
     *
     * @return int|null
     */
    public function subscriptionExpirationDate(): ?int
    {
        return $this->subscriptionExpirationDate;
    }

    /**
     * Check if the payment is a recurring payment for a subscription.
     *
     * @return bool
     */
    public function isRecurring(): bool
    {
        return $this->isRecurring;
    }

    /**
     * Check if the payment is the first payment for a subscription.
     *
     * @return bool
     */
    public function isFirstRecurring(): bool
    {
        return $this->isFirstRecurring;
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
     * @return string
     */
    public function providerPaymentChargeId(): string
    {
        return $this->providerPaymentChargeId;
    }

    /**
     * Check if the payment has a subscription expiration date.
     *
     * @return bool
     */
    public function hasSubscriptionExpiration(): bool
    {
        return $this->subscriptionExpirationDate !== null;
    }

    /**
     * Check if the payment has a shipping option.
     *
     * @return bool
     */
    public function hasShippingOption(): bool
    {
        return $this->shippingOptionId !== null;
    }

    /**
     * Check if the payment has order info.
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
     * Convert the SuccessfulPayment to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'currency' => $this->currency,
            'total_amount' => $this->totalAmount,
            'invoice_payload' => $this->invoicePayload,
            'subscription_expiration_date' => $this->subscriptionExpirationDate,
            'is_recurring' => $this->isRecurring,
            'is_first_recurring' => $this->isFirstRecurring,
            'shipping_option_id' => $this->shippingOptionId,
            'order_info' => $this->orderInfo?->toArray(),
            'telegram_payment_charge_id' => $this->telegramPaymentChargeId,
            'provider_payment_charge_id' => $this->providerPaymentChargeId,
        ], fn ($value) => $value !== null);
    }
}
