<?php declare(strict_types=1);

namespace Telegram\Objects\Exceptions;

/**
 * Exception thrown for payment-related validation errors.
 * 
 * Handles invoice, payment, and currency validation failures.
 */
class PaymentException extends TelegramException
{
    /**
     * Create an exception for invalid currency codes.
     *
     * @param string $currency The invalid currency code
     * @return static
     */
    public static function invalidCurrency(string $currency): static
    {
        return new static("Invalid currency code: {$currency}");
    }

    /**
     * Create an exception for invalid payment amounts.
     *
     * @param int $amount The invalid amount
     * @param string $reason The reason why the amount is invalid
     * @return static
     */
    public static function invalidAmount(int $amount, string $reason): static
    {
        return new static("Invalid payment amount {$amount}: {$reason}");
    }

    /**
     * Create an exception for invalid provider tokens.
     *
     * @param string $token The invalid provider token
     * @return static
     */
    public static function invalidProviderToken(string $token): static
    {
        return new static("Invalid payment provider token: {$token}");
    }

    /**
     * Create an exception for invoice validation failures.
     *
     * @param string $field The field that failed validation
     * @param string $reason The reason for validation failure
     * @return static
     */
    public static function invalidInvoiceData(string $field, string $reason): static
    {
        return new static("Invalid invoice data for field '{$field}': {$reason}");
    }

    /**
     * Create an exception for shipping address validation failures.
     *
     * @param string $field The address field that failed validation
     * @param string $value The invalid value
     * @return static
     */
    public static function invalidShippingAddress(string $field, string $value): static
    {
        return new static("Invalid shipping address field '{$field}': {$value}");
    }

    /**
     * Create an exception for payment processing failures.
     *
     * @param string $paymentId The payment identifier
     * @param string $reason The reason for processing failure
     * @return static
     */
    public static function paymentProcessingFailed(string $paymentId, string $reason): static
    {
        return new static("Payment processing failed for payment {$paymentId}: {$reason}");
    }
}