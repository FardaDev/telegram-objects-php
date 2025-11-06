<?php

declare(strict_types=1);

namespace Telegram\Objects\Support;

/**
 * Lightweight date/time wrapper for Telegram timestamps.
 *
 * Provides essential date/time functionality without Carbon dependency.
 */
class TelegramDateTime
{
    private \DateTimeImmutable $dateTime;

    private function __construct(\DateTimeImmutable $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    /**
     * Create a TelegramDateTime from a Unix timestamp.
     *
     * @param int $timestamp
     * @return self
     */
    public static function fromTimestamp(int $timestamp): self
    {
        $dateTime = (new \DateTimeImmutable('now', new \DateTimeZone('UTC')))->setTimestamp($timestamp);

        return new self($dateTime);
    }

    /**
     * Create a TelegramDateTime from a DateTime object.
     *
     * @param \DateTimeInterface $dateTime
     * @return self
     */
    public static function fromDateTime(\DateTimeInterface $dateTime): self
    {
        if ($dateTime instanceof \DateTimeImmutable) {
            return new self($dateTime);
        }

        return new self(\DateTimeImmutable::createFromInterface($dateTime));
    }

    /**
     * Create a TelegramDateTime for the current time.
     *
     * @return self
     */
    public static function now(): self
    {
        return new self(new \DateTimeImmutable());
    }

    /**
     * Get the Unix timestamp.
     *
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->dateTime->getTimestamp();
    }

    /**
     * Convert to ISO 8601 string format.
     *
     * @return string
     */
    public function toISOString(): string
    {
        return $this->dateTime->format(\DateTimeInterface::ATOM);
    }

    /**
     * Format the date/time using a custom format.
     *
     * @param string $format
     * @return string
     */
    public function format(string $format): string
    {
        return $this->dateTime->format($format);
    }

    /**
     * Get the underlying DateTimeImmutable object.
     *
     * @return \DateTimeImmutable
     */
    public function toDateTime(): \DateTimeImmutable
    {
        return $this->dateTime;
    }

    /**
     * Compare with another TelegramDateTime.
     *
     * @param TelegramDateTime $other
     * @return int Returns -1, 0, or 1
     */
    public function compare(TelegramDateTime $other): int
    {
        return $this->dateTime <=> $other->dateTime;
    }

    /**
     * Check if this date/time is before another.
     *
     * @param TelegramDateTime $other
     * @return bool
     */
    public function isBefore(TelegramDateTime $other): bool
    {
        return $this->compare($other) < 0;
    }

    /**
     * Check if this date/time is after another.
     *
     * @param TelegramDateTime $other
     * @return bool
     */
    public function isAfter(TelegramDateTime $other): bool
    {
        return $this->compare($other) > 0;
    }

    /**
     * Check if this date/time equals another.
     *
     * @param TelegramDateTime $other
     * @return bool
     */
    public function equals(TelegramDateTime $other): bool
    {
        return $this->compare($other) === 0;
    }

    /**
     * Convert to string representation.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->toISOString();
    }
}
